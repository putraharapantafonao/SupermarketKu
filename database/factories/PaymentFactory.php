<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $paid = fake()->numberBetween(50000, 200000);

        return [
            'transaction_id' => Transaction::factory(),
            'method' => fake()->randomElement(['cash', 'qris', 'transfer', 'ewallet', 'debit']),
            'paid_amount' => $paid,
            'change_amount' => fake()->numberBetween(0, 50000),
            'card_bank' => null,
            'trace_number' => null,
        ];
    }

    public function cash(): static
    {
        return $this->state(['method' => 'cash']);
    }

    public function nonCash(): static
    {
        return $this->state(fn(array $attrs) => [
            'method' => fake()->randomElement(['qris', 'transfer', 'ewallet', 'debit']),
            'change_amount' => 0,
        ]);
    }
}
