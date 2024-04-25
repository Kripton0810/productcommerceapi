<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_create_a_product()
    {
        // Assuming you have a user created with Passport and have obtained a token
        // $user = \App\Models\User::factory()->create();
        // $token = $user->createToken('TestToken')->accessToken;

        $headers = [
            'Accept' => 'application/json',
            // 'Authorization' => 'Bearer ' . $token,
        ];

        $response = $this->withHeaders($headers)->postJson('/api/product/store', [
            'name' => 'Test Product',
            'sku' => 'TEST001',
            'price' => 10.99,
            'rate' => 4,
            'stock' => 100,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data" => [
                    "name",
                    "sku",
                    "price",
                    "rate",
                    "stock",
                    "updated_at",
                    "created_at",
                    "id"
                ],
                "message"
            ]);
    }

    /** @test */
    public function test_it_can_list_products()
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/product');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function test_it_can_show_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson('/api/product/' . $product->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data" => [
                    "name",
                    "sku",
                    "price",
                    "rate",
                    "stock",
                    "updated_at",
                    "created_at",
                    "id"
                ],
                "message"
            ]);
    }

    /** @test */
    public function test_it_can_update_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->putJson('/api/product/' . $product->id, [
            'name' => 'Updated Product',
            'sku' => 'TEST002',
            'price' => 15.99,
            'rate' => 5,
            'stock' => 150,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                "success" => true,
                "data" => [
                    "name" => "Updated Product",
                    "sku" => "TEST002",
                    "price" => 15.99,
                    "rate" => 5,
                    "stock" => 150,
                    "updated_at" => true,
                    "created_at" => true,
                    "id" => $product->id
                ],
                "message" => "Product has been updated successfully"
            ]);
    }
    /** @test */
    public function test_it_can_update_wrong_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->putJson('/api/product/' . $product->id, [
            'name' => 'Updated Product',
            'sku' => 'TEST002',
            'price' => "abcd",
            'rate' => "alpha",
            'stock' => 150,
        ]);
        $response->assertStatus(422)->assertJson([
            "status" => false,
            "message" => "Error while validation",
            "errors" => [
                "price" => [
                    "The price must be a number."
                ],
                "rate" => [
                    "The rate must be an integer."
                ]
            ]
        ]);
    }

    /** @test */
    public function test_it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson('/api/product/' . $product->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
