<?php

namespace Tests\Feature;

use App\Models\Promotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestData;
use Tests\TestCase;

class PromotionControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = $this->createUser('Owner');
    }

    public function test_index_membutuhkan_role_owner(): void
    {
        $kasir = $this->createUser('Kasir');
        $response = $this->actingAs($kasir)->get(route('promotions.index'));
        $response->assertStatus(403);
    }

    public function test_store_menyimpan_promo_baru(): void
    {
        $product = $this->createProduct();
        $response = $this->actingAs($this->owner)->post(route('promotions.store'), [
            'name' => 'Promo Akhir Tahun',
            'product_id' => $product->id,
            'type' => 'percent',
            'value' => 10,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(7)->format('Y-m-d'),
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('promotions', ['name' => 'Promo Akhir Tahun']);
    }

    public function test_store_validasi_type_enum(): void
    {
        $response = $this->actingAs($this->owner)->post(route('promotions.store'), [
            'name' => 'Invalid',
            'type' => 'invalid_type',
            'value' => 10,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(7)->format('Y-m-d'),
        ]);
        $response->assertSessionHasErrors('type');
    }

    public function test_destroy_menghapus_promo(): void
    {
        $promotion = Promotion::factory()->create();
        $response = $this->actingAs($this->owner)->delete(route('promotions.destroy', $promotion));
        $response->assertRedirect();
        $this->assertDatabaseMissing('promotions', ['id' => $promotion->id]);
    }
}
