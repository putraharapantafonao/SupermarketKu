<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    protected array $roles = [
        ['name' => 'Owner'],
        ['name' => 'Admin'],
        ['name' => 'Kasir'],
        ['name' => 'Gudang'],
    ];

    public function run(): void
    {
        foreach ($this->roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
