<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Administrator',
                'slug' => Role::ADMIN,
                'description' => 'Full administrative access to manage parking lots, rates, spots, bookings, and users.',
            ],
            [
                'id' => 2,
                'name' => 'Customer',
                'slug' => Role::CUSTOMER,
                'description' => 'End customer access to reserve parking spots, manage registered vehicles, and view bookings.',
            ],
            [
                'id' => 3,
                'name' => 'Operator',
                'slug' => Role::OPERATOR,
                'description' => 'On-premise parking lot operator handling manual check-ins, check-outs, and lane monitoring.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
