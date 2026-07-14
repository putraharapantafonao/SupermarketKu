<?php

namespace Database\Factories;

use App\Models\TransactionDetail;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionDetailFactory extends Factory
{
    protected $model = TransactionDetail::class;

    public function definition(): array
    {
        $price = fake()->numberBetween(2000, 50000);
        $quantity = fake()->numberBetween(1, 10);

        return [
            'transaction_id' => Transaction::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $price * $quantity,
        ];
    }
}
