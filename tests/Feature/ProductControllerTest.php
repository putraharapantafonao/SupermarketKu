<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestData;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser('Admin');
    }

    public function test_index_menampilkan_daftar_produk(): void
    {
        Product::factory()->count(3)->create();
        $response = $this->actingAs($this->user)->get(route('products.index'));
        $response->assertStatus(200);
    }

    public function test_store_menyimpan_produk_baru(): void
    {
        $category = $this->createCategory();
        $supplier = $this->createSupplier();
        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'barcode' => '8999999999999',
            'name' => 'Produk Test',
            'purchase_price' => 5000,
            'selling_price' => 7500,
            'stock' => 100,
            'minimum_stock' => 10,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['barcode' => '8999999999999']);
    }

    public function test_store_validasi_barcode_unique(): void
    {
        $existing = $this->createProduct(['barcode' => '1111111111111']);
        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'category_id' => $existing->category_id,
            'barcode' => '1111111111111',
            'name' => 'Duplikat',
            'purchase_price' => 1000,
            'selling_price' => 2000,
            'stock' => 10,
            'minimum_stock' => 5,
        ]);
        $response->assertSessionHasErrors('barcode');
    }

    public function test_destroy_menghapus_produk(): void
    {
        $product = $this->createProduct();
        $response = $this->actingAs($this->user)->delete(route('products.destroy', $product));
        $response->assertRedirect();
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_update_memperbarui_produk(): void
    {
        $product = $this->createProduct();
        $response = $this->actingAs($this->user)->put(route('products.update', $product), [
            'category_id' => $product->category_id,
            'barcode' => $product->barcode,
            'name' => 'Nama Baru',
            'purchase_price' => 5000,
            'selling_price' => 8000,
            'stock' => 50,
            'minimum_stock' => 10,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Nama Baru']);
    }
}
