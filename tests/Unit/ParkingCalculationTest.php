<?php

namespace Tests\Unit;

use App\Models\ParkingLocation;
use App\Models\ParkingSlot;
use Tests\TestCase;

class ParkingCalculationTest extends TestCase
{
    public function test_standard_slot_inherits_location_rate(): void
    {
        $location = new ParkingLocation(['parking_charges' => 25.00]);
        $slot = new ParkingSlot([
            'slot_type' => 'Standard',
            'price' => null,
        ]);
        $slot->setRelation('parkingLocation', $location);

        $this->assertEquals(25.00, $slot->effective_price);
    }

    public function test_slot_price_override_takes_precedence(): void
    {
        $location = new ParkingLocation(['parking_charges' => 25.00]);
        $slot = new ParkingSlot([
            'slot_type' => 'Standard',
            'price' => 45.00,
        ]);
        $slot->setRelation('parkingLocation', $location);

        $this->assertEquals(45.00, $slot->effective_price);
    }
}
