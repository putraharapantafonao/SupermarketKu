<?php

namespace Database\Factories;

use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'product_id' => Product::factory(),
            'type' => fake()->randomElement(['percent', 'nominal']),
            'value' => fake()->numberBetween(10, 100),
            'start_date' => now()->subDays(1)->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
        ];
    }

    public function percent(int $percent = 10): static
    {
        return $this->state(['type' => 'percent', 'value' => $percent]);
    }

    public function nominal(int $amount = 5000): static
    {
        return $this->state(['type' => 'nominal', 'value' => $amount]);
    }

    public function expired(): static
    {
        return $this->state([
            'start_date' => now()->subDays(60)->format('Y-m-d'),
            'end_date' => now()->subDays(1)->format('Y-m-d'),
        ]);
    }

    public function active(): static
    {
        return $this->state([
            'start_date' => now()->subDays(1)->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
        ]);
    }
}
