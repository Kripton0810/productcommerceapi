<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_success_register()
    {
        $this->artisan('optimize:clear');
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '6209265435',
        ];

        $response = $this->postJson('/api/customer/store', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'city_id',
                    'state_id',
                    'created_at',
                    'updated_at',
                ],
                'message'
            ]);
    }
}
