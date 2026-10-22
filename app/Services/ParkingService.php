<?php

namespace App\Services;

use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use App\Models\ParkingZone;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ParkingService
{
    /**
     * Get all active parking locations for user search.
     */
    public function getActiveLocations(array $filters = []): Collection
    {
        $query = ParkingLocation::where('status', 'Active')
            ->with(['zones.slots']);

        if (! empty($filters['city'])) {
            $query->where('city', 'like', "%{$filters['city']}%");
        }

        if (! empty($filters['location'])) {
            $query->where('name', 'like', "%{$filters['location']}%");
        }

        if (! empty($filters['vehicle_type'])) {
            $query->whereHas('zones', function ($q) use ($filters) {
                $q->where('vehicle_type', $filters['vehicle_type']);
            });
        }

        return $query->get();
    }

    /**
     * Get paginated locations for admin.
     */
    public function getAdminLocations(int $perPage = 10): LengthAwarePaginator
    {
        return ParkingLocation::withCount(['zones', 'slots'])
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function createLocation(array $data): ParkingLocation
    {
        return ParkingLocation::create($data);
    }

    public function updateLocation(int $id, array $data): bool
    {
        $location = ParkingLocation::findOrFail($id);
        return $location->update($data);
    }

    public function deleteLocation(int $id): bool
    {
        $location = ParkingLocation::findOrFail($id);
        return (bool) $location->delete();
    }

    /**
     * Zones
     */
    public function createZone(array $data): ParkingZone
    {
        return DB::transaction(function () use ($data) {
            $zone = ParkingZone::create($data);

            // Update location capacity
            $location = $zone->parkingLocation;
            $location->total_capacity = $location->slots()->count();
            $location->save();

            return $zone;
        });
    }

    public function updateZone(int $id, array $data): bool
    {
        $zone = ParkingZone::findOrFail($id);
        return $zone->update($data);
    }

    public function deleteZone(int $id): bool
    {
        $zone = ParkingZone::findOrFail($id);
        return (bool) $zone->delete();
    }

    /**
     * Batch generate parking slots for a zone.
     * Example: prefix "A", start "1", count "5" -> creates A01, A02, A03, A04, A05.
     */
    public function batchCreateSlots(ParkingZone $zone, string $prefix, int $start, int $count, ?string $floor = null, ?string $section = null): int
    {
        return DB::transaction(function () use ($zone, $prefix, $start, $count, $floor, $section) {
            $created = 0;
            $prefix = strtoupper(trim($prefix));

            for ($i = $start; $i < ($start + $count); $i++) {
                $slotNumber = sprintf('%s%02d', $prefix, $i);

                $exists = ParkingSlot::where('zone_id', $zone->id)
                    ->where('slot_number', $slotNumber)
                    ->exists();

                if (! $exists) {
                    ParkingSlot::create([
                        'parking_location_id' => $zone->parking_location_id,
                        'zone_id' => $zone->id,
                        'slot_number' => $slotNumber,
                        'vehicle_type' => $zone->vehicle_type,
                        'floor' => $floor ?? $zone->floor,
                        'section' => $section ?? $zone->section,
                        'status' => ParkingSlot::STATUS_AVAILABLE,
                    ]);
                    $created++;
                }
            }

            // Update zone and location totals
            $zone->total_slots = $zone->slots()->count();
            $zone->save();

            $location = $zone->parkingLocation;
            $location->total_capacity = $location->slots()->count();
            $location->save();

            return $created;
        });
    }

    /**
     * Update individual slot status.
     */
    public function updateSlotStatus(int $slotId, string $status): bool
    {
        $slot = ParkingSlot::findOrFail($slotId);
        return $slot->update(['status' => $status]);
    }

    /**
     * Delete individual slot.
     */
    public function deleteSlot(int $slotId): bool
    {
        $slot = ParkingSlot::findOrFail($slotId);
        $zone = $slot->zone;

        $deleted = $slot->delete();

        if ($zone) {
            $zone->total_slots = $zone->slots()->count();
            $zone->save();

            $location = $zone->parkingLocation;
            if ($location) {
                $location->total_capacity = $location->slots()->count();
                $location->save();
            }
        }

        return (bool) $deleted;
    }

    /**
     * Dynamic system-wide slot statistics.
     */
    public function getGlobalSlotStats(): array
    {
        $total = ParkingSlot::count();
        $occupied = ParkingSlot::where('status', ParkingSlot::STATUS_OCCUPIED)->count();
        $reserved = ParkingSlot::where('status', ParkingSlot::STATUS_RESERVED)->count();

        return [
            'total' => $total,
            'available' => ParkingSlot::where('status', ParkingSlot::STATUS_AVAILABLE)->count(),
            'reserved' => $reserved,
            'occupied' => $occupied,
            'maintenance' => ParkingSlot::where('status', ParkingSlot::STATUS_MAINTENANCE)->count(),
            'blocked' => ParkingSlot::where('status', ParkingSlot::STATUS_BLOCKED)->count(),
            'occupancy_rate' => $total > 0 ? round((($occupied + $reserved) / $total) * 100, 1) : 0,
        ];
    }
}
