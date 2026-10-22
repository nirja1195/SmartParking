<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class BookingTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_customer_can_access_dashboard(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'driver@smartpark.com'],
            ['name' => 'Demo Driver', 'password' => bcrypt('password123'), 'status' => 'active']
        );

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }
}
