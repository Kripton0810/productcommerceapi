<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConsumerTrevexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_add_customer_from_company()
    {
        $userData = [
            'search' => 'FOO',
            'country_code' => 'IN',
        ];

        $response = $this->postJson('/api/customer/store', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'company_name',
                    'company_id',
                    'country',
                    'estd_date',
                    'state',
                    'address_line1',
                    'address_line2',
                    'created_at',
                    'updated_at',
                ],
                'message'
            ]);
    }

    public function test_show_customer_from_company()
    {
        $customer = Customer::factory()->create();


        $response = $this->getJson('/api/customer/' . $customer->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    "company_address_detailsID",
                    "address_line_1",
                    "address_line_2",
                    "postal_code",
                    "area",
                    "city",
                    "state",
                    "country",
                    "telephone",
                    "email",
                    "working_hours",
                    "status",
                    "is_deleted",
                    "timestamp",
                    "wb_win",
                    "banner",
                    "logo",
                    "registered_company_name",
                    "date_of_establishment",
                    "company_description",
                    "currency",
                    "number_of_employees",
                    "twitter",
                    "facebook",
                    "instagram",
                    "linkedin",
                    "website",
                    "trade_license",
                    "date_of_issue",
                    "date_of_expiry",
                    "license_no",
                    "registration_body",
                    "legal_form",
                    "activities",
                    "TRN_no"
                ],
                'message'
            ]);
    }
}
