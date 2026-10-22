<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class CheckOverdueReservations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'parking:check-overdue';

    /**
     * The console command description.
     */
    protected $description = 'Scan active reservations and flag overdue bookings with penalty calculations';

    /**
     * Execute the console command.
     */
    public function handle(BookingService $bookingService): int
    {
        $this->info('Scanning for overdue parking sessions...');

        $flagged = $bookingService->flagOverdueBookings();

        $this->info("Completed. Flagged {$flagged} booking(s) as overdue.");

        return Command::SUCCESS;
    }
}
