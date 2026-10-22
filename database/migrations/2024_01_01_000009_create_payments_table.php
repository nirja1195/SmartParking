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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('transaction_id', 50)->unique();
            $table->enum('payment_method', ['UPI', 'Debit Card', 'Credit Card', 'Net Banking', 'Wallet'])->default('UPI');
            $table->decimal('amount', 8, 2);
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED', 'CANCELLED', 'REFUNDED'])->default('PENDING')->index();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
