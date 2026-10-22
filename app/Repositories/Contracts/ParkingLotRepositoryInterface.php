<?php

namespace App\Repositories\Contracts;

use App\Models\ParkingLot;
use Illuminate\Database\Eloquent\Collection;

interface ParkingLotRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveLots(): Collection;

    public function findByCode(string $code): ?ParkingLot;

    public function getLotsWithAvailableSpotCounts(): Collection;
}
