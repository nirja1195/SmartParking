<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("SmartPark Reservation Confirmed - #{$this->booking->booking_reference}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your parking reservation #{$this->booking->booking_reference} is confirmed!")
            ->line("Parking Lot: {$this->booking->spot?->lot?->name}")
            ->line("Spot Number: {$this->booking->spot?->spot_number} (Floor: {$this->booking->spot?->floor})")
            ->line("Start Time: {$this->booking->start_time->toDayDateTimeString()}")
            ->line("End Time: {$this->booking->end_time->toDayDateTimeString()}")
            ->line("Total Reserved Amount: $".number_format($this->booking->total_amount, 2))
            ->action('View Reservation Details', url('/customer/bookings'))
            ->line('Thank you for choosing SmartPark!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_reference' => $this->booking->booking_reference,
            'lot_name' => $this->booking->spot?->lot?->name,
            'spot_number' => $this->booking->spot?->spot_number,
            'total_amount' => $this->booking->total_amount,
            'start_time' => $this->booking->start_time->toIso8601String(),
        ];
    }
}
