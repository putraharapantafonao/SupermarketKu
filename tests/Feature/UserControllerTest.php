<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestData;
use Tests\TestCase;

class UserControllerTest extends TestCase
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
        $response = $this->actingAs($kasir)->get(route('users.index'));
        $response->assertStatus(403);
    }

    public function test_store_menyimpan_user_baru(): void
    {
        $role = $this->createRole('Admin');
        $response = $this->actingAs($this->owner)->post(route('users.store'), [
            'name' => 'User Baru',
            'email' => 'baru@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $role->id,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'baru@test.com']);
    }

    public function test_tidak_bisa_menghapus_diri_sendiri(): void
    {
        $response = $this->actingAs($this->owner)->delete(route('users.destroy', $this->owner));
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $this->owner->id]);
    }
}
