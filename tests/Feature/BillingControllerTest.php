<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BillingControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_create_bill()
    {
        $customer = Customer::factory()->create();
        $products = Product::factory()->count(3)->create();

        $data = [
            'customer_id' => $customer->id,
            'products' => [
                ['id' => $products[0]->id, 'quantity' => 2],
                ['id' => $products[1]->id, 'quantity' => 1],
                ['id' => $products[2]->id, 'quantity' => 3],
            ]
        ];

        $response = $this->postJson('/api/billing', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data" => [
                    "customer_id",
                    "bill_number",
                    "total_amount",
                    "updated_at",
                    "created_at",
                    "id"
                ],
                "message"
            ]);
    }

    public function test_show_all_bills()
    {
        $customer = Customer::factory()->create();
        $products = Product::factory()->count(10)->create();

        $data = [
            'customer_id' => $customer->id,
            'products' => [
                ['id' => $products[0]->id, 'quantity' => 2],
                ['id' => $products[1]->id, 'quantity' => 1],
                ['id' => $products[2]->id, 'quantity' => 3],
            ]
        ];

        $response = $this->postJson('/api/billing', $data);
        $response = $this->postJson('/api/billing', $data);
        $response = $this->postJson('/api/billing', $data);
        $response = $this->postJson('/api/billing', $data);
        $response = $this->postJson('/api/billing', $data);

        $response = $this->getJson('/api/billing');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }
}
