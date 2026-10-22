<?php

namespace App\Repositories\Eloquent;

use App\Models\ParkingLot;
use App\Repositories\Contracts\ParkingLotRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ParkingLotRepository extends BaseRepository implements ParkingLotRepositoryInterface
{
    public function __construct(ParkingLot $model)
    {
        parent::__construct($model);
    }

    public function getActiveLots(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->with(['spots' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();
    }

    public function findByCode(string $code): ?ParkingLot
    {
        return $this->model->where('code', $code)->first();
    }

    public function getLotsWithAvailableSpotCounts(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->withCount([
                'spots as total_spots_count',
                'spots as available_spots_count' => function ($query) {
                    $query->where('is_active', true)->where('is_occupied', false);
                },
            ])
            ->get();
    }
}
