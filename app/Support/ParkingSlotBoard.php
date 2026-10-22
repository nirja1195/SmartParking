<?php
namespace App\Support;

use App\Models\ParkingSlot;

class ParkingSlotBoard
{
    public static function types(): array
    {
        return [
            'car' => ['label' => 'Car Slots', 'single' => 'Car Slot', 'icon' => 'car-front-fill'],
            'bike' => ['label' => 'Bike Slots', 'single' => 'Bike Slot', 'icon' => 'bicycle'],
            'ev' => ['label' => 'EV Slots', 'single' => 'EV Slot', 'icon' => 'ev-station-fill'],
            'handicap' => ['label' => 'Handicap Slots', 'single' => 'Handicap Slot', 'icon' => 'person-wheelchair'],
            'other' => ['label' => 'Other Slots', 'single' => 'Parking Slot', 'icon' => 'truck-front-fill'],
        ];
    }

    public static function category(ParkingSlot $slot): string
    {
        $classification = strtolower(trim($slot->slot_type ?? ''));
        if (in_array($classification, ['disabled', 'accessible', 'handicap', 'handicapped', 'handicap accessible'], true)) return 'handicap';
        if (strtolower($slot->vehicle_type) === 'ev' || in_array($classification, ['ev', 'ev charging', 'electric'], true)) return 'ev';
        return match (strtolower($slot->vehicle_type)) { 'car' => 'car', 'bike' => 'bike', default => 'other' };
    }

    public static function floor(ParkingSlot $slot): string
    {
        $floor = trim($slot->floor ?: $slot->zone?->floor ?: 'Unassigned Floor');
        return match (strtolower($floor)) {
            'ground', 'ground floor', 'g', '0', 'level 0' => 'Ground Floor',
            'first', 'first floor', '1', 'level 1', 'floor 1' => 'First Floor',
            'second', 'second floor', '2', 'level 2', 'floor 2' => 'Second Floor',
            default => $floor,
        };
    }

    public static function floorRank(string $floor): int
    {
        return match ($floor) { 'Ground Floor' => 0, 'First Floor' => 1, 'Second Floor' => 2, default => str_contains(strtolower($floor), 'basement') ? -1 : 3 };
    }
}
