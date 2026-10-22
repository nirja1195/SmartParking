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
        Schema::create('parking_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('city')->index();
            $table->string('state')->nullable();
            $table->string('pincode', 15)->nullable();
            $table->string('contact_number', 30)->nullable();
            $table->time('opening_time')->default('06:00:00');
            $table->time('closing_time')->default('23:00:00');
            $table->unsignedInteger('total_capacity')->default(0);
            $table->text('description')->nullable();
            $table->string('facilities')->nullable(); // e.g. EV Charging, Covered, Security, Restroom
            $table->decimal('parking_charges', 8, 2)->default(20.00); // Base hourly rate
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_locations');
    }
};
