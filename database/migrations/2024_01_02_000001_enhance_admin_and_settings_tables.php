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
        // 1. Enhance parking_locations with coordinates
        Schema::table('parking_locations', function (Blueprint $table) {
            if (!Schema::hasColumn('parking_locations', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('pincode');
            }
            if (!Schema::hasColumn('parking_locations', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });

        // 2. Enhance parking_zones with zone_code and description
        Schema::table('parking_zones', function (Blueprint $table) {
            if (!Schema::hasColumn('parking_zones', 'zone_code')) {
                $table->string('zone_code', 30)->nullable()->after('zone_name');
            }
            if (!Schema::hasColumn('parking_zones', 'description')) {
                $table->text('description')->nullable()->after('total_slots');
            }
        });

        // 3. Enhance parking_slots with slot_type, custom price, and description
        Schema::table('parking_slots', function (Blueprint $table) {
            if (!Schema::hasColumn('parking_slots', 'slot_type')) {
                $table->string('slot_type', 50)->default('Standard')->after('slot_number'); // Standard, Premium, VIP, Accessible, EV
            }
            if (!Schema::hasColumn('parking_slots', 'price')) {
                $table->decimal('price', 8, 2)->nullable()->after('slot_type'); // null = fallback to location rate
            }
            if (!Schema::hasColumn('parking_slots', 'description')) {
                $table->text('description')->nullable()->after('section');
            }
        });

        // 4. Enhance vehicles with brand
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'brand')) {
                $table->string('brand', 60)->nullable()->after('vehicle_type'); // e.g. Toyota, Honda, Hyundai, Tesla
            }
        });

        // 5. Create settings table for full system & CMS dynamism
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique()->index();
                $table->longText('value')->nullable();
                $table->string('group', 50)->default('general')->index(); // general, pricing, rules, homepage
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');

        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'brand')) {
                $table->dropColumn('brand');
            }
        });

        Schema::table('parking_slots', function (Blueprint $table) {
            $cols = array_filter(['slot_type', 'price', 'description'], fn($c) => Schema::hasColumn('parking_slots', $c));
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        Schema::table('parking_zones', function (Blueprint $table) {
            $cols = array_filter(['zone_code', 'description'], fn($c) => Schema::hasColumn('parking_zones', $c));
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        Schema::table('parking_locations', function (Blueprint $table) {
            $cols = array_filter(['latitude', 'longitude'], fn($c) => Schema::hasColumn('parking_locations', $c));
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
