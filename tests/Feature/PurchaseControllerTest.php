<?php

namespace Tests\Feature;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesTestData;

class PurchaseControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $gudang;
    private $product;
    private $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gudang = $this->createUser('Gudang');
        $this->product = $this->createProduct(['stock' => 10]);
        $this->supplier = $this->createSupplier();
    }

    public function test_index_shows_purchases(): void
    {
        $response = $this->actingAs($this->gudang)->get(route('purchases.index'));

        $response->assertStatus(200);
        $response->assertViewHas('purchases');
    }

    public function test_create_shows_form(): void
    {
        $response = $this->actingAs($this->gudang)->get(route('purchases.create'));

        $response->assertStatus(200);
        $response->assertViewHas(['suppliers', 'products']);
    }

    public function test_store_creates_purchase_and_increments_stock(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('purchases.store'), [
            'supplier_id' => $this->supplier->id,
            'product_id' => [$this->product->id],
            'quantity' => [20],
            'purchase_price' => [3000],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->product->refresh();
        $this->assertEquals(30, $this->product->stock);

        $this->assertDatabaseHas('purchases', [
            'supplier_id' => $this->supplier->id,
        ]);
    }

    public function test_store_creates_stock_movement(): void
    {
        $this->actingAs($this->gudang)->post(route('purchases.store'), [
            'supplier_id' => $this->supplier->id,
            'product_id' => [$this->product->id],
            'quantity' => [15],
            'purchase_price' => [5000],
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'in',
            'quantity' => 15,
        ]);
    }

    public function test_store_creates_purchase_details(): void
    {
        $this->actingAs($this->gudang)->post(route('purchases.store'), [
            'supplier_id' => $this->supplier->id,
            'product_id' => [$this->product->id],
            'quantity' => [10],
            'purchase_price' => [4000],
        ]);

        $this->assertDatabaseHas('purchase_details', [
            'product_id' => $this->product->id,
            'quantity' => 10,
            'purchase_price' => 4000,
            'subtotal' => 40000,
        ]);
    }

    public function test_show_displays_purchase_detail(): void
    {
        $purchase = Purchase::create([
            'supplier_id' => $this->supplier->id,
            'user_id' => $this->gudang->id,
            'purchase_code' => 'PO-TEST-001',
            'total_price' => 50000,
        ]);

        $response = $this->actingAs($this->gudang)->get(route('purchases.show', $purchase->id));

        $response->assertStatus(200);
        $response->assertViewHas('purchase');
    }

    public function test_store_validates_supplier_id_required(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('purchases.store'), [
            'product_id' => [$this->product->id],
            'quantity' => [10],
            'purchase_price' => [5000],
        ]);

        $response->assertSessionHasErrors('supplier_id');
    }

    public function test_store_validates_product_id_array_required(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('purchases.store'), [
            'supplier_id' => $this->supplier->id,
            'quantity' => [10],
            'purchase_price' => [5000],
        ]);

        $response->assertSessionHasErrors('product_id');
    }

    public function test_store_validates_quantity_required(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('purchases.store'), [
            'supplier_id' => $this->supplier->id,
            'product_id' => [$this->product->id],
            'purchase_price' => [5000],
        ]);

        $response->assertSessionHasErrors('quantity');
    }

    public function test_store_validates_purchase_price_required(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('purchases.store'), [
            'supplier_id' => $this->supplier->id,
            'product_id' => [$this->product->id],
            'quantity' => [10],
        ]);

        $response->assertSessionHasErrors('purchase_price');
    }

    public function test_kasir_cannot_access_purchases(): void
    {
        $kasir = $this->createUser('Kasir');

        $response = $this->actingAs($kasir)->get(route('purchases.index'));

        $response->assertStatus(403);
    }

    public function test_store_requires_authentication(): void
    {
        $response = $this->post(route('purchases.store'), [
            'supplier_id' => $this->supplier->id,
            'product_id' => [$this->product->id],
            'quantity' => [10],
            'purchase_price' => [5000],
        ]);

        $response->assertRedirect();
    }
}
