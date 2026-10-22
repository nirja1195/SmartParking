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
        Schema::create('parking_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_location_id')->constrained('parking_locations')->cascadeOnDelete();
            $table->string('zone_name'); // e.g. Zone A, Zone B, Basement 1
            $table->enum('vehicle_type', ['Car', 'Bike', 'Auto', 'Bus', 'Truck', 'EV'])->default('Car');
            $table->string('floor')->default('Ground');
            $table->string('section')->nullable(); // e.g. Section 1, North Wing
            $table->unsignedInteger('total_slots')->default(0);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            $table->index(['parking_location_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_zones');
    }
};
