<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_location_id')->constrained('parking_locations')->cascadeOnDelete();
            $table->foreignId('zone_id')->constrained('parking_zones')->cascadeOnDelete();
            $table->string('slot_number'); // e.g. A01, A02, B05
            $table->enum('vehicle_type', ['Car', 'Bike', 'Auto', 'Bus', 'Truck', 'EV'])->default('Car');
            $table->string('floor')->default('Ground');
            $table->string('section')->nullable();
            $table->enum('status', ['AVAILABLE', 'RESERVED', 'OCCUPIED', 'MAINTENANCE', 'BLOCKED'])->default('AVAILABLE')->index();
            $table->timestamps();

            $table->unique(['zone_id', 'slot_number']);
            $table->index(['parking_location_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_slots');
    }
};
