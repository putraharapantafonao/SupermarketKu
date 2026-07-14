<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestData;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase, CreatesTestData;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser('Admin');
    }

    public function test_index_menampilkan_daftar_kategori(): void
    {
        Category::factory()->count(3)->create();
        $response = $this->actingAs($this->user)->get(route('categories.index'));
        $response->assertStatus(200);
    }

    public function test_store_menyimpan_kategori_baru(): void
    {
        $response = $this->actingAs($this->user)->post(route('categories.store'), [
            'name' => 'Minuman Ringan',
            'description' => 'Kategori minuman',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Minuman Ringan']);
    }

    public function test_store_validasi_nama_required(): void
    {
        $response = $this->actingAs($this->user)->post(route('categories.store'), ['name' => '']);
        $response->assertSessionHasErrors('name');
    }

    public function test_destroy_menghapus_kategori(): void
    {
        $category = $this->createCategory();
        $response = $this->actingAs($this->user)->delete(route('categories.destroy', $category));
        $response->assertRedirect();
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }
}
