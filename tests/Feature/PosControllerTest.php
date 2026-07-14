<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesTestData;

class PosControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $kasir;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRoles();
        $this->kasir = $this->createUser('Kasir');
        $this->product = $this->createProduct(['stock' => 50, 'minimum_stock' => 10]);
    }

    public function test_pos_page_can_be_accessed_by_kasir(): void
    {
        $response = $this->actingAs($this->kasir)->get(route('pos.index'));

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_pos(): void
    {
        $response = $this->get(route('pos.index'));

        $response->assertRedirect();
    }

    public function test_user_can_add_product_to_cart(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertRedirect();
        $this->assertTrue(session()->has('cart'));
        $this->assertEquals($this->product->id, session('cart')[$this->product->id]['id']);
        $this->assertEquals(1, session('cart')[$this->product->id]['quantity']);
    }

    public function test_user_can_scan_valid_barcode(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('pos.scan'), [
            'barcode' => $this->product->barcode,
        ]);

        $response->assertRedirect();
        $this->assertTrue(session()->has('cart'));
        $this->assertEquals(1, session('cart')[$this->product->id]['quantity']);
    }

    public function test_scan_invalid_barcode_returns_error(): void
    {
        $response = $this->actingAs($this->kasir)->post(route('pos.scan'), [
            'barcode' => '0000000000000',
        ]);

        $response->assertRedirect();
        $this->assertEquals('Produk dengan barcode tersebut tidak ditemukan.', session('error'));
    }

    public function test_user_can_increase_quantity(): void
    {
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
        $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $this->product->id,
        ]);

        $this->actingAs($this->kasir)->post(route('pos.increase'), [
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->kasir)->post(route('pos.decrease'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertRedirect();
        $this->assertEquals(1, session('cart')[$this->product->id]['quantity']);
    }

    public function test_decrease_at_one_removes_item_from_cart(): void
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

    public function test_user_can_clear_cart(): void
    {
        $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->kasir)->post(route('pos.clear'));

        $response->assertRedirect();
        $this->assertFalse(session()->has('cart'));
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

    public function test_checkout_with_empty_cart_shows_error(): void
    {
        $response = $this->actingAs($this->kasir)->get(route('pos.checkout'));

        $response->assertRedirect();
        $this->assertEquals('Keranjang masih kosong.', session('error'));
    }

    public function test_increase_beyond_stock_returns_error(): void
    {
        $product = $this->createProduct(['stock' => 2]);

        $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $product->id,
        ]);

        // Add again twice: qty goes 1 -> 2 -> should fail on 3rd
        $this->actingAs($this->kasir)->post(route('pos.increase'), ['product_id' => $product->id]);

        $response = $this->actingAs($this->kasir)->post(route('pos.increase'), [
            'product_id' => $product->id,
        ]);

        $response->assertRedirect();
        $this->assertEquals('Jumlah melebihi stok tersedia.', session('error'));
    }

    public function test_scan_out_of_stock_product_shows_error(): void
    {
        $product = $this->createProduct(['stock' => 0]);

        $response = $this->actingAs($this->kasir)->post(route('pos.scan'), [
            'barcode' => $product->barcode,
        ]);

        $response->assertRedirect();
        $this->assertEquals('Stok produk habis.', session('error'));
    }

    public function test_add_product_without_cart_does_not_error(): void
    {
        $this->actingAs($this->kasir)->post(route('pos.add'), [
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->kasir)->post(route('pos.increase'), [
            'product_id' => $this->product->id,
        ]);

        $this->assertEquals(2, session('cart')[$this->product->id]['quantity']);
    }
}
