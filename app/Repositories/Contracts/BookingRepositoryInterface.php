<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookingRepositoryInterface extends BaseRepositoryInterface
{
    public function findByReference(string $reference): ?Booking;

    public function getUserBookings(int $userId, int $perPage = 10): LengthAwarePaginator;

    public function getActiveBookingsForSpot(int $spotId, string $startTime, string $endTime): Collection;

    public function getRecentBookings(int $limit = 10): Collection;

    public function getOverdueBookings(): Collection;
}
