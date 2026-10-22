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
        Schema::table('parking_locations', function (Blueprint $table) {
            if (! Schema::hasColumn('parking_locations', 'location_type')) {
                $table->string('location_type')->default('Commercial')->after('name');
            }
            if (! Schema::hasColumn('parking_locations', 'area')) {
                $table->string('area')->nullable()->after('address');
            }
            if (! Schema::hasColumn('parking_locations', 'image')) {
                $table->string('image')->nullable()->after('facilities');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_locations', function (Blueprint $table) {
            if (Schema::hasColumn('parking_locations', 'location_type')) {
                $table->dropColumn('location_type');
            }
            if (Schema::hasColumn('parking_locations', 'area')) {
                $table->dropColumn('area');
            }
            if (Schema::hasColumn('parking_locations', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
