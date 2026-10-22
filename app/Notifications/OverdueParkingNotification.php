<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueParkingNotification extends Notification implements ShouldQueue
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
            ->error()
            ->subject("URGENT: SmartPark Parking Overdue - #{$this->booking->booking_reference}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your parking duration for booking #{$this->booking->booking_reference} has expired.")
            ->line("Scheduled Check-Out: {$this->booking->end_time->toDayDateTimeString()}")
            ->line("Overdue penalties of $".config('parking.overdue_penalty_rate_per_hour', 15.00)."/hour will apply.")
            ->line('Please check out your vehicle immediately at the terminal or contact staff.')
            ->action('View Live Status', url('/customer/bookings'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_reference' => $this->booking->booking_reference,
            'status' => 'overdue',
            'overdue_since' => $this->booking->end_time->toIso8601String(),
        ];
    }
}
