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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id', 30)->unique(); // e.g. SP202609210001
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('parking_location_id')->constrained('parking_locations')->cascadeOnDelete();
            $table->foreignId('zone_id')->constrained('parking_zones')->cascadeOnDelete();
            $table->foreignId('slot_id')->constrained('parking_slots')->cascadeOnDelete();
            $table->date('booking_date')->index();
            $table->dateTime('entry_time')->index();
            $table->dateTime('expected_exit_time')->index();
            $table->dateTime('actual_exit_time')->nullable();
            $table->unsignedInteger('duration')->default(1); // In hours
            $table->decimal('amount', 8, 2)->default(0.00);
            $table->enum('booking_status', ['PENDING', 'CONFIRMED', 'ACTIVE', 'COMPLETED', 'CANCELLED'])->default('PENDING')->index();
            $table->enum('payment_status', ['PENDING', 'PAID', 'FAILED', 'CANCELLED', 'REFUNDED'])->default('PENDING')->index();
            $table->text('qr_code')->nullable();
            $table->timestamps();

            $table->index(['slot_id', 'booking_date', 'booking_status']);
            $table->index(['user_id', 'booking_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
