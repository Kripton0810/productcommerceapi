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
    public function create_bill_test()
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

        $response = $this->postJson('/api/billings', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'bill_number', 'total_amount', 'customer_id', 'products']);
    }
}
