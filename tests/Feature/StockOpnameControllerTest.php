<?php

namespace Tests\Feature;

use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesTestData;

class StockOpnameControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $gudang;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gudang = $this->createUser('Gudang');
        $this->product = $this->createProduct(['stock' => 50]);
    }

    public function test_index_page_loads(): void
    {
        $response = $this->actingAs($this->gudang)->get(route('stock-opname.index'));

        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_update_increases_stock(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('stock-opname.update'), [
            'product_id' => $this->product->id,
            'real_stock' => 75,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->product->refresh();
        $this->assertEquals(75, $this->product->stock);
    }

    public function test_update_decreases_stock(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('stock-opname.update'), [
            'product_id' => $this->product->id,
            'real_stock' => 30,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->product->refresh();
        $this->assertEquals(30, $this->product->stock);
    }

    public function test_update_unchanged_stock_returns_error(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('stock-opname.update'), [
            'product_id' => $this->product->id,
            'real_stock' => 50,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Stok tidak berubah.');
    }

    public function test_update_creates_stock_movement_on_increase(): void
    {
        $this->actingAs($this->gudang)->post(route('stock-opname.update'), [
            'product_id' => $this->product->id,
            'real_stock' => 70,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'in',
            'quantity' => 20,
        ]);
    }

    public function test_update_creates_stock_movement_on_decrease(): void
    {
        $this->actingAs($this->gudang)->post(route('stock-opname.update'), [
            'product_id' => $this->product->id,
            'real_stock' => 40,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'out',
            'quantity' => 10,
        ]);
    }

    public function test_update_validates_product_id_required(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('stock-opname.update'), [
            'real_stock' => 60,
        ]);

        $response->assertSessionHasErrors('product_id');
    }

    public function test_update_validates_product_id_exists(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('stock-opname.update'), [
            'product_id' => 99999,
            'real_stock' => 60,
        ]);

        $response->assertSessionHasErrors('product_id');
    }

    public function test_update_validates_real_stock_required(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('stock-opname.update'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertSessionHasErrors('real_stock');
    }

    public function test_update_validates_real_stock_integer(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('stock-opname.update'), [
            'product_id' => $this->product->id,
            'real_stock' => 'abc',
        ]);

        $response->assertSessionHasErrors('real_stock');
    }

    public function test_update_validates_real_stock_min_zero(): void
    {
        $response = $this->actingAs($this->gudang)->post(route('stock-opname.update'), [
            'product_id' => $this->product->id,
            'real_stock' => -1,
        ]);

        $response->assertSessionHasErrors('real_stock');
    }

    public function test_kasir_cannot_access_stock_opname(): void
    {
        $kasir = $this->createUser('Kasir');

        $response = $this->actingAs($kasir)->get(route('stock-opname.index'));

        $response->assertStatus(403);
    }

    public function test_owner_can_access_stock_opname(): void
    {
        $owner = $this->createUser('Owner');

        $response = $this->actingAs($owner)->get(route('stock-opname.index'));

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access(): void
    {
        $response = $this->get(route('stock-opname.index'));

        $response->assertRedirect();
    }
}
