<?php

namespace Database\Factories;

use App\Enums\Order\OrderStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement(OrderStatusEnum::values()),
            'amount' => fake()->numberBetween(0, 999999),
            'user_id' => User::factory()->create(),
        ];
    }
}
