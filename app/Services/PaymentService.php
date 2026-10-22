<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\ParkingSlot;
use App\Models\Payment;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Process simulated payment for college/demo project.
     * Transitions Booking: PENDING -> CONFIRMED.
     * Transitions Slot: AVAILABLE -> RESERVED.
     */
    public function processPayment(Booking $booking, string $paymentMethod): Payment
    {
        return DB::transaction(function () use ($booking, $paymentMethod) {
            // Lock slot and booking
            $lockedBooking = Booking::where('id', $booking->id)->lockForUpdate()->firstOrFail();
            $slot = ParkingSlot::where('id', $lockedBooking->slot_id)->lockForUpdate()->firstOrFail();

            if ($lockedBooking->booking_status !== Booking::STATUS_PENDING && $lockedBooking->booking_status !== Booking::STATUS_CONFIRMED) {
                throw new Exception('This booking is not eligible for payment.');
            }

            $transactionId = 'TXN' . strtoupper(Str::random(12));

            $payment = Payment::create([
                'booking_id' => $lockedBooking->id,
                'user_id' => $lockedBooking->user_id,
                'transaction_id' => $transactionId,
                'payment_method' => $paymentMethod,
                'amount' => $lockedBooking->amount,
                'status' => Payment::STATUS_SUCCESS,
                'paid_at' => Carbon::now(),
            ]);

            // Transition Booking to CONFIRMED
            $lockedBooking->update([
                'booking_status' => Booking::STATUS_CONFIRMED,
                'payment_status' => Booking::PAYMENT_PAID,
            ]);

            // Transition Slot from AVAILABLE to RESERVED
            $slot->update([
                'status' => ParkingSlot::STATUS_RESERVED,
            ]);

            // Create User Notification
            Notification::create([
                'user_id' => $lockedBooking->user_id,
                'title' => 'Payment Successful & Slot Reserved',
                'message' => "Payment of \${$lockedBooking->amount} received via {$paymentMethod}. Slot {$slot->slot_number} at {$lockedBooking->parkingLocation->name} is now RESERVED.",
                'type' => 'success',
            ]);

            // Create Admin Notification
            Notification::create([
                'admin_id' => 1,
                'title' => 'New Confirmed Booking',
                'message' => "Booking #{$lockedBooking->booking_id} confirmed by {$lockedBooking->user->name} for Slot {$slot->slot_number}.",
                'type' => 'info',
            ]);

            return $payment;
        });
    }
}
