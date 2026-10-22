<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * Create a new booking with strict double-booking prevention.
     */
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $slot = ParkingSlot::where('id', $data['slot_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($slot->status !== ParkingSlot::STATUS_AVAILABLE) {
                throw new Exception('Sorry, this slot is no longer available.');
            }

            $bookingDate = Carbon::parse($data['booking_date'])->toDateString();
            $entryTime = Carbon::parse($data['entry_time']);
            $exitTime = Carbon::parse($data['expected_exit_time']);

            if ($exitTime->lessThanOrEqualTo($entryTime)) {
                throw new Exception('Expected exit time must be after entry time.');
            }

            // Check for overlapping bookings for this slot on this date
            $overlap = Booking::where('slot_id', $slot->id)
                ->where('booking_date', $bookingDate)
                ->whereIn('booking_status', [Booking::STATUS_CONFIRMED, Booking::STATUS_ACTIVE])
                ->where(function ($query) use ($entryTime, $exitTime) {
                    $query->whereBetween('entry_time', [$entryTime, $exitTime])
                        ->orWhereBetween('expected_exit_time', [$entryTime, $exitTime])
                        ->orWhere(function ($q) use ($entryTime, $exitTime) {
                            $q->where('entry_time', '<=', $entryTime)
                                ->where('expected_exit_time', '>=', $exitTime);
                        });
                })
                ->lockForUpdate()
                ->exists();

            if ($overlap) {
                throw new Exception('Sorry, this slot is no longer available for the requested time window.');
            }

            // Calculate duration in hours
            $durationMinutes = max(60, $entryTime->diffInMinutes($exitTime));
            $durationHours = (int) ceil($durationMinutes / 60);

            $location = ParkingLocation::findOrFail($data['parking_location_id']);
            $hourlyRate = (float) $location->parking_charges;
            $amount = round($durationHours * $hourlyRate, 2);

            // Generate unique Booking ID (e.g. SP202609210001)
            $datePrefix = 'SP' . date('Ymd');
            $countToday = Booking::where('created_at', '>=', Carbon::today())->count() + 1;
            $bookingId = sprintf('%s%04d', $datePrefix, $countToday);

            while (Booking::where('booking_id', $bookingId)->exists()) {
                $bookingId = sprintf('%s%04d', $datePrefix, ++$countToday);
            }

            $booking = Booking::create([
                'booking_id' => $bookingId,
                'user_id' => $data['user_id'],
                'vehicle_id' => $data['vehicle_id'] ?? null,
                'parking_location_id' => $location->id,
                'zone_id' => $data['zone_id'],
                'slot_id' => $slot->id,
                'booking_date' => $bookingDate,
                'entry_time' => $entryTime,
                'expected_exit_time' => $exitTime,
                'duration' => $durationHours,
                'amount' => $amount,
                'booking_status' => Booking::STATUS_PENDING,
                'payment_status' => Booking::PAYMENT_PENDING,
                'qr_code' => $bookingId,
            ]);

            return $booking;
        });
    }

    /**
     * Cancel an existing booking.
     */
    public function cancelBooking(int $bookingId, int $userId): Booking
    {
        return DB::transaction(function () use ($bookingId, $userId) {
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array($booking->booking_status, [Booking::STATUS_ACTIVE, Booking::STATUS_COMPLETED])) {
                throw new Exception('Active or completed bookings cannot be cancelled.');
            }

            $booking->update([
                'booking_status' => Booking::STATUS_CANCELLED,
            ]);

            // Release the slot if it was reserved for this booking
            $slot = $booking->slot;
            if ($slot && $slot->status === ParkingSlot::STATUS_RESERVED) {
                $slot->update(['status' => ParkingSlot::STATUS_AVAILABLE]);
            }

            Notification::create([
                'user_id' => $booking->user_id,
                'title' => 'Booking Cancelled',
                'message' => "Your reservation #{$booking->booking_id} has been cancelled.",
                'type' => 'warning',
            ]);

            return $booking;
        });
    }

    /**
     * Get user bookings with status filter.
     */
    public function getUserBookings(int $userId, ?string $tab = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Booking::where('user_id', $userId)
            ->with(['parkingLocation', 'zone', 'slot', 'vehicle', 'payment'])
            ->orderBy('id', 'desc');

        if ($tab === 'upcoming') {
            $query->where('booking_status', Booking::STATUS_CONFIRMED);
        } elseif ($tab === 'active') {
            $query->where('booking_status', Booking::STATUS_ACTIVE);
        } elseif ($tab === 'completed') {
            $query->where('booking_status', Booking::STATUS_COMPLETED);
        } elseif ($tab === 'cancelled') {
            $query->where('booking_status', Booking::STATUS_CANCELLED);
        }

        return $query->paginate($perPage);
    }
}
