<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesTestData;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $kasir;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kasir = $this->createUser('Kasir');
        $this->product = $this->createProduct(['stock' => 100]);
    }

    public function test_index_shows_transactions(): void
    {
        $this->createTransactionWithDetail();

        $response = $this->actingAs($this->kasir)->get(route('transactions.index'));

        $response->assertStatus(200);
        $response->assertViewHas('transactions');
    }

    public function test_index_requires_authentication(): void
    {
        $response = $this->get(route('transactions.index'));

        $response->assertRedirect();
    }

    public function test_show_displays_transaction_detail(): void
    {
        $user = $this->createUser('Kasir');
        $transaction = $this->createTransactionWithDetail(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('transactions.show', $transaction->id));

        $response->assertStatus(200);
        $response->assertViewHas('transaction');
    }

    public function test_store_creates_new_transaction(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'product_id' => [$this->product->id],
            'quantity' => [2],
            'method' => 'cash',
            'paid_amount' => 999999,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->kasir->id,
        ]);
    }

    public function test_store_with_qris_method_creates_transaction(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'product_id' => [$this->product->id],
            'quantity' => [1],
            'method' => 'qris',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_store_with_discount_creates_transaction(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'product_id' => [$this->product->id],
            'quantity' => [2],
            'method' => 'cash',
            'paid_amount' => 999999,
            'discount' => 1000,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_store_validates_method_required(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'product_id' => [$this->product->id],
            'quantity' => [2],
        ]);

        $response->assertSessionHasErrors('method');
    }

    public function test_store_validates_method_in(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'product_id' => [$this->product->id],
            'quantity' => [2],
            'method' => 'bitcoin',
        ]);

        $response->assertSessionHasErrors('method');
    }

    public function test_store_validates_product_id_required(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'quantity' => [2],
            'method' => 'cash',
        ]);

        $response->assertSessionHasErrors('product_id');
    }

    public function test_store_validates_product_id_exists(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'product_id' => [99999],
            'quantity' => [2],
            'method' => 'cash',
        ]);

        $response->assertSessionHasErrors('product_id.*');
    }

    public function test_store_validates_quantity_min_one(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'product_id' => [$this->product->id],
            'quantity' => [0],
            'method' => 'cash',
        ]);

        $response->assertSessionHasErrors('quantity.*');
    }

    public function test_store_requires_authentication(): void
    {
        $response = $this->post(route('transactions.store'), [
            'product_id' => [$this->product->id],
            'quantity' => [2],
            'method' => 'cash',
        ]);

        $response->assertRedirect();
    }

    public function test_store_decrements_product_stock(): void
    {
        $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'product_id' => [$this->product->id],
            'quantity' => [5],
            'method' => 'cash',
            'paid_amount' => 999999,
        ]);

        $this->product->refresh();
        $this->assertEquals(95, $this->product->stock);
    }

    public function test_store_creates_stock_movement(): void
    {
        $this->actingAs($this->kasir)->post(route('transactions.store'), [
            'product_id' => [$this->product->id],
            'quantity' => [3],
            'method' => 'cash',
            'paid_amount' => 999999,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'out',
            'quantity' => 3,
        ]);
    }

    public function test_show_returns_404_for_nonexistent_transaction(): void
    {
        $response = $this->actingAs($this->kasir)->get(route('transactions.show', 99999));

        $response->assertStatus(404);
    }
}
