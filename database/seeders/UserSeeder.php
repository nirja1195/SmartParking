<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('slug', Role::ADMIN)->first();
        $customerRole = Role::where('slug', Role::CUSTOMER)->first();

        // 1. Master Administrator
        $admin = User::updateOrCreate(
            ['email' => 'admin@smartpark.test'],
            [
                'role_id' => $adminRole->id,
                'name' => 'System Admin',
                'phone' => '+1 800-555-0199',
                'password' => Hash::make('Admin@123456'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Demo Customer
        $customer = User::updateOrCreate(
            ['email' => 'customer@smartpark.test'],
            [
                'role_id' => $customerRole->id,
                'name' => 'John Doe',
                'phone' => '+1 800-555-0123',
                'password' => Hash::make('Customer@123456'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Register vehicles for customer
        Vehicle::updateOrCreate(
            ['license_plate' => 'SP-77-CAR'],
            [
                'user_id' => $customer->id,
                'make' => 'Tesla',
                'model' => 'Model 3',
                'color' => 'Pearl White',
                'vehicle_type' => 'ev',
            ]
        );

        Vehicle::updateOrCreate(
            ['license_plate' => 'NY-44-SUV'],
            [
                'user_id' => $customer->id,
                'make' => 'Toyota',
                'model' => 'RAV4',
                'color' => 'Midnight Black',
                'vehicle_type' => 'standard',
            ]
        );
    }
}
