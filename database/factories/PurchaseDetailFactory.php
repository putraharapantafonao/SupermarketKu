<?php

namespace Database\Factories;

use App\Models\PurchaseDetail;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseDetailFactory extends Factory
{
    protected $model = PurchaseDetail::class;

    public function definition(): array
    {
        $price = fake()->numberBetween(1000, 50000);
        $quantity = fake()->numberBetween(10, 200);

        return [
            'purchase_id' => Purchase::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'purchase_price' => $price,
            'subtotal' => $price * $quantity,
        ];
    }
}
