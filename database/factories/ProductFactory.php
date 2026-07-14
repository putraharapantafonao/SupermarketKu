<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $category = Category::factory();
        $supplier = Supplier::factory();

        return [
            'category_id' => $category,
            'supplier_id' => $supplier,
            'barcode' => fake()->unique()->numerify('899##########'),
            'name' => fake()->unique()->word(),
            'purchase_price' => fake()->numberBetween(1000, 50000),
            'selling_price' => fn(array $attrs) => $attrs['purchase_price'] + fake()->numberBetween(500, 10000),
            'stock' => fake()->numberBetween(0, 200),
            'minimum_stock' => fake()->numberBetween(5, 20),
            'expired_date' => fake()->dateTimeBetween('+6 months', '+3 years')->format('Y-m-d'),
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn(array $attrs) => [
            'stock' => fake()->numberBetween(0, 5),
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(['stock' => 0]);
    }

    public function inStock(): static
    {
        return $this->state(['stock' => fake()->numberBetween(50, 500)]);
    }
}
