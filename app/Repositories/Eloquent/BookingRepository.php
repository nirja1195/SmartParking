<?php

namespace App\Repositories\Eloquent;

use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }

    public function findByReference(string $reference): ?Booking
    {
        return $this->model
            ->with(['user', 'spot.lot', 'vehicle', 'payment'])
            ->where('booking_reference', $reference)
            ->first();
    }

    public function getUserBookings(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['spot.lot', 'vehicle', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getActiveBookingsForSpot(int $spotId, string $startTime, string $endTime): Collection
    {
        return $this->model
            ->where('parking_spot_id', $spotId)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED, Booking::STATUS_ACTIVE])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->get();
    }

    public function getRecentBookings(int $limit = 10): Collection
    {
        return $this->model
            ->with(['user', 'spot.lot', 'vehicle', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getOverdueBookings(): Collection
    {
        return $this->model
            ->where('status', Booking::STATUS_ACTIVE)
            ->where('end_time', '<', now())
            ->with(['user', 'spot.lot', 'vehicle'])
            ->get();
    }
}
