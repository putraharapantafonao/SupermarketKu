<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'user_id' => User::factory(),
            'purchase_code' => 'PO-TEST-' . now()->format('YmdHis') . '-' . fake()->unique()->randomNumber(4),
            'total_price' => fake()->numberBetween(50000, 1000000),
            'status' => 'completed',
        ];
    }
}
