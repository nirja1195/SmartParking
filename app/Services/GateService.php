<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\ParkingEntry;
use App\Models\ParkingExit;
use App\Models\ParkingSlot;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class GateService
{
    /**
     * Confirm Vehicle Entry:
     * Slot: RESERVED -> OCCUPIED
     * Booking: CONFIRMED -> ACTIVE
     */
    public function confirmEntry(string $identifier, ?int $staffId = null, ?string $remarks = null): ParkingEntry
    {
        return DB::transaction(function () use ($identifier, $staffId, $remarks) {
            // Find booking by booking_id or vehicle_number
            $booking = Booking::where(function ($q) use ($identifier) {
                $q->where('booking_id', $identifier)
                    ->orWhereHas('vehicle', function ($v) use ($identifier) {
                        $v->where('vehicle_number', $identifier);
                    });
            })
            ->whereIn('booking_status', [Booking::STATUS_CONFIRMED, Booking::STATUS_PENDING])
            ->lockForUpdate()
            ->first();

            if (! $booking) {
                throw new Exception("No confirmed reservation found for identifier '{$identifier}'.");
            }

            $now = Carbon::now();

            // Transition Booking: CONFIRMED -> ACTIVE
            $booking->update([
                'booking_status' => Booking::STATUS_ACTIVE,
                'entry_time' => $now,
            ]);

            // Transition Slot: RESERVED -> OCCUPIED
            $slot = ParkingSlot::where('id', $booking->slot_id)->lockForUpdate()->firstOrFail();
            $slot->update([
                'status' => ParkingSlot::STATUS_OCCUPIED,
            ]);

            // Record entry
            $entry = ParkingEntry::create([
                'booking_id' => $booking->id,
                'vehicle_id' => $booking->vehicle_id,
                'slot_id' => $slot->id,
                'staff_id' => $staffId,
                'entry_time' => $now,
                'remarks' => $remarks,
            ]);

            // Notify user
            Notification::create([
                'user_id' => $booking->user_id,
                'title' => 'Vehicle Entry Confirmed',
                'message' => "Your vehicle has entered {$booking->parkingLocation->name}. Slot {$slot->slot_number} is now PARKED & ACTIVE.",
                'type' => 'info',
            ]);

            return $entry;
        });
    }

    /**
     * Confirm Vehicle Exit:
     * Slot: OCCUPIED -> AVAILABLE
     * Booking: ACTIVE -> COMPLETED
     */
    public function confirmExit(string $identifier, ?int $staffId = null, ?string $remarks = null): ParkingExit
    {
        return DB::transaction(function () use ($identifier, $staffId, $remarks) {
            $booking = Booking::where(function ($q) use ($identifier) {
                $q->where('booking_id', $identifier)
                    ->orWhereHas('vehicle', function ($v) use ($identifier) {
                        $v->where('vehicle_number', $identifier);
                    });
            })
            ->where('booking_status', Booking::STATUS_ACTIVE)
            ->lockForUpdate()
            ->first();

            if (! $booking) {
                throw new Exception("No active parked vehicle found for identifier '{$identifier}'.");
            }

            $now = Carbon::now();
            $entryTime = $booking->entry_time ?? $booking->created_at;
            $durationMinutes = max(1, $entryTime->diffInMinutes($now));

            // Calculate overtime penalty if exited after expected exit time
            $penalty = 0.00;
            if ($now->greaterThan($booking->expected_exit_time)) {
                $overdueMinutes = $booking->expected_exit_time->diffInMinutes($now);
                $overdueHours = (int) ceil($overdueMinutes / 60);
                $penaltyRate = 15.00; // Standard hourly penalty
                $penalty = round($overdueHours * $penaltyRate, 2);
            }

            $totalCharge = $booking->amount + $penalty;

            // Transition Booking: ACTIVE -> COMPLETED
            $booking->update([
                'booking_status' => Booking::STATUS_COMPLETED,
                'actual_exit_time' => $now,
            ]);

            // Transition Slot: OCCUPIED -> AVAILABLE
            $slot = ParkingSlot::where('id', $booking->slot_id)->lockForUpdate()->firstOrFail();
            $slot->update([
                'status' => ParkingSlot::STATUS_AVAILABLE,
            ]);

            // Record exit
            $exit = ParkingExit::create([
                'booking_id' => $booking->id,
                'slot_id' => $slot->id,
                'staff_id' => $staffId,
                'exit_time' => $now,
                'total_duration' => $durationMinutes,
                'penalty_charge' => $penalty,
                'total_charge' => $totalCharge,
                'remarks' => $remarks,
            ]);

            // Notify user
            Notification::create([
                'user_id' => $booking->user_id,
                'title' => 'Vehicle Exit Confirmed & Completed',
                'message' => "Your parking session at {$booking->parkingLocation->name} has concluded. Thank you for using SMARTPARK AI!",
                'type' => 'success',
            ]);

            return $exit;
        });
    }
}
