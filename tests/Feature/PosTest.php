<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosTest extends TestCase
{
    use RefreshDatabase;

    private $kasir;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'Kasir']);
        Role::create(['name' => 'Owner']);

        $kasirRole = Role::where('name', 'Kasir')->first();
        $this->kasir = User::factory()->create(['role_id' => $kasirRole->id]);

        $category = Category::create(['name' => 'Makanan']);
        $this->product = Product::create([
            'category_id' => $category->id,
            'barcode' => '1234567890123',
            'name' => 'Indomie Goreng',
            'purchase_price' => 2500,
            'selling_price' => 3500,
            'stock' => 50,
            'minimum_stock' => 10,
            'expired_date' => '2027-12-31',
        ]);
    }

    public function test_user_can_view_pos_page(): void
    {
        $response = $this->actingAs($this->kasir)->get(route('pos.index'));
        $response->assertStatus(200);
    }

    public function test_user_can_add_product_to_cart(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertRedirect();
        $this->assertTrue(session()->has('cart'));
        $this->assertEquals($this->product->id, session('cart')[$this->product->id]['id']);
    }

    public function test_user_can_scan_barcode(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('pos.scan'), [
            'barcode' => $this->product->barcode,
        ]);

        $response->assertRedirect();
        $this->assertTrue(session()->has('cart'));
    }

    public function test_scan_invalid_barcode_returns_error(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('pos.scan'), [
            'barcode' => '0000000000000',
        ]);

        $response->assertRedirect();
        $this->assertEquals('Produk dengan barcode tersebut tidak ditemukan.', session('error'));
    }

    public function test_cannot_add_out_of_stock_product(): void
    {
        $this->product->update(['stock' => 0]);

        $response = $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertRedirect();
        $this->assertEquals('Stok produk habis.', session('error'));
    }

    public function test_user_can_increase_quantity(): void
    {
        // First add to cart
        $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->kasir)->post(route('pos.increase'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertRedirect();
        $this->assertEquals(2, session('cart')[$this->product->id]['quantity']);
    }

    public function test_user_can_decrease_quantity(): void
    {
        // Add to cart (quantity = 1)
        $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $this->product->id,
        ]);

        // Increase to 2
        $this->actingAs($this->kasir)->post(route('pos.increase'), [
            'product_id' => $this->product->id,
        ]);

        // Decrease back to 1
        $response = $this->actingAs($this->kasir)->post(route('pos.decrease'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertRedirect();
        $this->assertEquals(1, session('cart')[$this->product->id]['quantity']);
    }

    public function test_decrease_at_one_removes_item(): void
    {
        $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->kasir)->post(route('pos.decrease'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertRedirect();
        $this->assertEmpty(session('cart'));
    }

    public function test_user_can_clear_cart(): void
    {
        $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->kasir)->post(route('pos.clear'));

        $response->assertRedirect();
        $this->assertFalse(session()->has('cart'));
    }

    public function test_user_can_remove_from_cart(): void
    {
        $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->kasir)->post(route('pos.remove'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertRedirect();
        $this->assertEmpty(session('cart'));
    }
}
