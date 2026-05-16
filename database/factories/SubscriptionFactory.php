<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(2, true),
            'category' => fake()->randomElement(Subscription::CATEGORIES),
            'price' => fake()->numberBetween(10000, 500000),
            'currency' => fake()->randomElement(Subscription::CURRENCIES),
            'billing_cycle' => fake()->randomElement(Subscription::BILLING_CYCLES),
            'next_due_date' => fake()->dateTimeBetween('now', '+3 months'),
            'is_active' => true,
        ];
    }
}
