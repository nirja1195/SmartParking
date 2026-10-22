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
        Schema::create('parking_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('slot_id')->constrained('parking_slots')->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->dateTime('entry_time');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'entry_time']);
        });

        Schema::create('parking_exits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('slot_id')->constrained('parking_slots')->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->dateTime('exit_time');
            $table->unsignedInteger('total_duration')->default(0); // in minutes
            $table->decimal('penalty_charge', 8, 2)->default(0.00);
            $table->decimal('total_charge', 8, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'exit_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_exits');
        Schema::dropIfExists('parking_entries');
    }
};
