<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    // use RefreshDatabase; // Reset the database after each test

    /**
     * Test validation error response.
     */
    public function test_validation_error_response()
    {
        $response = $this->postJson('/api/register', []);
        // dd($response->json());
        $response->assertStatus(422)->assertJson([
            'status' => false,
            'message' => 'Error while validation',
            'errors' => [
                'name' => [
                    0 => 'The name field is required.'
                ],
                'email' => [
                    0 => 'The email field is required.'
                ],
                'password' => [
                    0 => 'The password field is required.'
                ]
            ]
        ]);
    }

    /**
     * Test successful user registration.
     */
    public function test_successful_registration()
    {
        $this->artisan('optimize:clear');
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                    'accessToken',
                ],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'message' => 'User has been created successfully',
            ]);
    }

    // You may want to add more test cases to cover edge cases, such as duplicate emails, incorrect password confirmation, etc.
}
