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
        Schema::table('parking_services', function (Blueprint $table) {
            if (!Schema::hasColumn('parking_services', 'slug')) {
                $table->string('slug', 150)->nullable()->after('name');
            }
            if (!Schema::hasColumn('parking_services', 'short_description')) {
                $table->text('short_description')->nullable()->after('description');
            }
            if (!Schema::hasColumn('parking_services', 'button_text')) {
                $table->string('button_text', 100)->nullable()->default('Book Now')->after('icon');
            }
            if (!Schema::hasColumn('parking_services', 'button_url')) {
                $table->string('button_url', 255)->nullable()->after('button_text');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_services', function (Blueprint $table) {
            $table->dropColumn(['slug', 'short_description', 'button_text', 'button_url']);
        });
    }
};
