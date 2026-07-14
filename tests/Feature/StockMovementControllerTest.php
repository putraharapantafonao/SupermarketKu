<?php

namespace Tests\Feature;

use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesTestData;

class StockMovementControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
        $this->product = $this->createProduct();
    }

    public function test_owner_can_view_stock_movements(): void
    {
        $owner = $this->createUser('Owner');

        $response = $this->actingAs($owner)->get(route('stock-movements.index'));

        $response->assertStatus(200);
        $response->assertViewHas('stockMovements');
    }

    public function test_admin_can_view_stock_movements(): void
    {
        $admin = $this->createUser('Admin');

        $response = $this->actingAs($admin)->get(route('stock-movements.index'));

        $response->assertStatus(200);
    }

    public function test_gudang_can_view_stock_movements(): void
    {
        $gudang = $this->createUser('Gudang');

        $response = $this->actingAs($gudang)->get(route('stock-movements.index'));

        $response->assertStatus(200);
    }

    public function test_kasir_cannot_view_stock_movements(): void
    {
        $kasir = $this->createUser('Kasir');

        $response = $this->actingAs($kasir)->get(route('stock-movements.index'));

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_view_stock_movements(): void
    {
        $response = $this->get(route('stock-movements.index'));

        $response->assertRedirect();
    }

    public function test_stock_movements_shows_data(): void
    {
        $owner = $this->createUser('Owner');
        StockMovement::create([
            'product_id' => $this->product->id,
            'type' => 'in',
            'quantity' => 50,
            'description' => 'Pembelian awal',
        ]);

        $response = $this->actingAs($owner)->get(route('stock-movements.index'));

        $response->assertStatus(200);
    }
}
