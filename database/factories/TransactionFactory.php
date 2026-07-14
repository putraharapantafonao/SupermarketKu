<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Customer;
use App\Services\InvoiceService;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $total = fake()->numberBetween(10000, 500000);
        $discount = fake()->numberBetween(0, 5000);
        $tax = 0;

        return [
            'invoice_number' => 'INV-TEST-' . now()->format('YmdHis') . '-' . fake()->unique()->randomNumber(4),
            'user_id' => User::factory(),
            'customer_id' => Customer::factory(),
            'total_price' => $total,
            'discount' => $discount,
            'tax' => $tax,
            'grand_total' => max($total - $discount + $tax, 0),
            'status' => 'completed',
        ];
    }

    public function withCustomer(?Customer $customer = null): static
    {
        return $this->state(fn(array $attrs) => [
            'customer_id' => $customer?->id ?? Customer::factory(),
        ]);
    }

    public function walkIn(): static
    {
        return $this->state(['customer_id' => null]);
    }
}
