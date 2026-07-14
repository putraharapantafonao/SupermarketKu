<?php

namespace Tests\Feature;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestData;
use Tests\TestCase;

class SupplierControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser('Admin');
    }

    public function test_index_menampilkan_daftar_supplier(): void
    {
        Supplier::factory()->count(3)->create();
        $response = $this->actingAs($this->user)->get(route('suppliers.index'));
        $response->assertStatus(200);
    }

    public function test_store_menyimpan_supplier_baru(): void
    {
        $response = $this->actingAs($this->user)->post(route('suppliers.store'), [
            'name' => 'PT Maju Mundur',
            'phone' => '0215551234',
            'address' => 'Jl. Sudirman No. 10',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('suppliers', ['name' => 'PT Maju Mundur']);
    }

    public function test_store_validasi_nama_required(): void
    {
        $response = $this->actingAs($this->user)->post(route('suppliers.store'), ['name' => '']);
        $response->assertSessionHasErrors('name');
    }

    public function test_destroy_menghapus_supplier(): void
    {
        $supplier = $this->createSupplier();
        $response = $this->actingAs($this->user)->delete(route('suppliers.destroy', $supplier));
        $response->assertRedirect();
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }
}
