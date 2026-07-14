<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestData;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    public function test_process_checkout_creates_transaction(): void
    {
        $user = $this->createUser('Kasir');
        $product = $this->createProduct(['selling_price' => 3500, 'stock' => 50]);
        $this->actingAs($user);

        $cart = [['id' => $product->id, 'quantity' => 2, 'price' => 3500, 'subtotal' => 7000]];
        $transaction = TransactionService::processCheckout($cart, [
            'method' => 'cash', 'paid_amount' => 10000,
        ]);

        $this->assertNotNull($transaction->id);
        $this->assertEquals(7000, $transaction->grand_total);
        $this->assertStringStartsWith('INV-', $transaction->invoice_number);
        $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
        $this->assertDatabaseHas('transaction_details', ['transaction_id' => $transaction->id]);
        $this->assertDatabaseHas('payments', ['transaction_id' => $transaction->id]);
        $this->assertDatabaseHas('stock_movements', ['product_id' => $product->id]);
    }

    public function test_empty_cart_throws_exception(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Keranjang masih kosong.');

        TransactionService::processCheckout([], ['method' => 'cash']);
    }

    public function test_stock_is_decremented(): void
    {
        $user = $this->createUser('Kasir');
        $product = $this->createProduct(['selling_price' => 3000, 'stock' => 20]);
        $this->actingAs($user);

        $cart = [['id' => $product->id, 'quantity' => 5, 'price' => 3000, 'subtotal' => 15000]];
        TransactionService::processCheckout($cart, [
            'method' => 'cash', 'paid_amount' => 20000,
        ]);

        $product->refresh();
        $this->assertEquals(15, $product->stock);
    }

    public function test_insufficient_stock_throws_exception(): void
    {
        $user = $this->createUser('Kasir');
        $product = $this->createProduct(['selling_price' => 3000, 'stock' => 3]);
        $this->actingAs($user);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Stok produk ' . $product->name . ' tidak mencukupi');

        TransactionService::processCheckout(
            [['id' => $product->id, 'quantity' => 10, 'price' => 3000, 'subtotal' => 30000]],
            ['method' => 'cash', 'paid_amount' => 30000]
        );
    }

    public function test_discount_exceeding_total_throws_exception(): void
    {
        $user = $this->createUser('Kasir');
        $product = $this->createProduct(['selling_price' => 2000, 'stock' => 10]);
        $this->actingAs($user);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid discount amount');

        TransactionService::processCheckout(
            [['id' => $product->id, 'quantity' => 1, 'price' => 2000, 'subtotal' => 2000]],
            ['method' => 'cash', 'paid_amount' => 2000, 'discount' => 5000]
        );
    }

    public function test_non_cash_payment_sets_paid_amount_to_grand_total(): void
    {
        $user = $this->createUser('Kasir');
        $product = $this->createProduct(['selling_price' => 2000, 'stock' => 10]);
        $this->actingAs($user);

        $cart = [['id' => $product->id, 'quantity' => 2, 'price' => 2000, 'subtotal' => 4000]];
        $transaction = TransactionService::processCheckout($cart, [
            'method' => 'qris', 'paid_amount' => 0,
        ]);

        $payment = $transaction->payment;
        $this->assertEquals('qris', $payment->method);
        $this->assertEquals(4000, $payment->paid_amount);
        $this->assertEquals(0, $payment->change_amount);
    }
}
