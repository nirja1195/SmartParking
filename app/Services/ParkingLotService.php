<?php

namespace App\Services;

use App\Models\ParkingLot;
use App\Models\ParkingSpot;
use App\Repositories\Contracts\ParkingLotRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParkingLotService
{
    protected ParkingLotRepositoryInterface $parkingLotRepo;

    public function __construct(ParkingLotRepositoryInterface $parkingLotRepo)
    {
        $this->parkingLotRepo = $parkingLotRepo;
    }

    public function getActiveLots(): Collection
    {
        return $this->parkingLotRepo->getLotsWithAvailableSpotCounts();
    }

    public function getLotById(int $id): ?ParkingLot
    {
        return $this->parkingLotRepo->find($id, ['*'], ['spots']);
    }

    /**
     * Create a new parking lot and automatically generate spots based on floor layouts.
     */
    public function createParkingLot(array $data, array $floors = ['Ground']): ParkingLot
    {
        return DB::transaction(function () use ($data, $floors) {
            $lot = $this->parkingLotRepo->create([
                'name' => $data['name'],
                'code' => strtoupper($data['code']),
                'address' => $data['address'],
                'city' => $data['city'],
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'total_spots' => $data['total_spots'],
                'hourly_rate' => $data['hourly_rate'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Auto-generate parking spot records
            $totalSpots = (int) $data['total_spots'];
            $spotsPerFloor = (int) ceil($totalSpots / count($floors));
            $generated = 0;

            foreach ($floors as $floor) {
                for ($i = 1; $i <= $spotsPerFloor && $generated < $totalSpots; $i++) {
                    $spotType = ($i % 5 === 0) ? 'ev' : (($i % 8 === 0) ? 'compact' : 'standard');

                    ParkingSpot::create([
                        'parking_lot_id' => $lot->id,
                        'spot_number' => sprintf('%s-%02d', substr($floor, 0, 2), $i),
                        'floor' => $floor,
                        'spot_type' => $spotType,
                        'is_active' => true,
                        'is_occupied' => false,
                        'hourly_rate_override' => null,
                    ]);

                    $generated++;
                }
            }

            Log::info("Parking lot created: {$lot->name} ({$lot->code}) with {$generated} spots.");

            return $lot;
        });
    }

    /**
     * Update parking lot details.
     */
    public function updateParkingLot(int $id, array $data): bool
    {
        return $this->parkingLotRepo->update($id, $data);
    }

    /**
     * Delete parking lot.
     */
    public function deleteParkingLot(int $id): bool
    {
        return $this->parkingLotRepo->delete($id);
    }
}
