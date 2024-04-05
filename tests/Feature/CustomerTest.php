<?php

namespace Tests\Feature;

use App\Models\Customer;
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
        $userData = [
            'name' => 'John Doe',
            'email' => 'joahn@example.com',
            'phone' => '1234567990',
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
    public function test_customer_unsuccess_register()
    {
        $userData = [];

        $response = $this->postJson('/api/customer/store', $userData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'errors' => [],
                'message'
            ]);
    }
    public function test_customer_update_success()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'joahn@example.com',
            'phone' => '1234567990',
        ];

        $response = $this->postJson('/api/customer/store', $userData);

        $customerId = Customer::where('email', 'joahn@example.com')->first()->id; // Set the ID of the customer you want to update
        // dd($customerId);

        $response = $this->putJson("/api/customer/update/" . $customerId, [
            'name' => 'Updated Name',
            'phone' => '1234567190',
        ]);

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
