<?php

namespace Database\Factories;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['in', 'out']),
            'quantity' => fake()->numberBetween(1, 100),
            'description' => fake()->sentence(),
        ];
    }

    public function stockIn(): static
    {
        return $this->state(['type' => 'in']);
    }

    public function stockOut(): static
    {
        return $this->state(['type' => 'out']);
    }
}
