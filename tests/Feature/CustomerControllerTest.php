<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestData;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser('Kasir');
    }

    public function test_index_menampilkan_daftar_pelanggan(): void
    {
        Customer::factory()->count(3)->create();
        $response = $this->actingAs($this->user)->get(route('customers.index'));
        $response->assertStatus(200);
    }

    public function test_store_menyimpan_pelanggan_baru(): void
    {
        $response = $this->actingAs($this->user)->post(route('customers.store'), [
            'name' => 'Budi',
            'phone' => '08123456789',
            'points' => 100,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('customers', ['name' => 'Budi']);
    }

    public function test_store_validasi_nama_required(): void
    {
        $response = $this->actingAs($this->user)->post(route('customers.store'), ['name' => '']);
        $response->assertSessionHasErrors('name');
    }

    public function test_destroy_menghapus_pelanggan(): void
    {
        $customer = $this->createCustomer();
        $response = $this->actingAs($this->user)->delete(route('customers.destroy', $customer));
        $response->assertRedirect();
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
