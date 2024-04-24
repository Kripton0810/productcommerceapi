<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->unique()->word,
            "phone" => null,
            "email" => null,
            "company_id" => "TRXIN0000012",
            "company_name" => $this->faker->unique()->word,
            "country" => "IN",
            "estd_date" => "2014-02-01",
            "state" => $this->faker->unique()->word,
            "address_line1" => $this->faker->sentence,
            "address_line2" => $this->faker->sentence
        ];
    }
}
