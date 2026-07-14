<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local')) {
            $this->command->warn('⚠️  Seeder user password berasal dari env(SEEDER_USER_PASSWORD). Ubah password setelah login!');
        }

        $owner = Role::where('name', 'Owner')->first();
        $admin = Role::where('name', 'Admin')->first();
        $kasir = Role::where('name', 'Kasir')->first();
        $gudang = Role::where('name', 'Gudang')->first();

        User::firstOrCreate(
            ['email' => 'owner@supermarketku.com'],
            [
                'name'       => 'Owner SupermarketKu',
                'password'   => Hash::make(env('SEEDER_USER_PASSWORD', 'password')),
                'role_id'    => $owner->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@supermarketku.com'],
            [
                'name'       => 'Admin SupermarketKu',
                'password'   => Hash::make(env('SEEDER_USER_PASSWORD', 'password')),
                'role_id'    => $admin->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@supermarketku.com'],
            [
                'name'       => 'Kasir SupermarketKu',
                'password'   => Hash::make(env('SEEDER_USER_PASSWORD', 'password')),
                'role_id'    => $kasir->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'gudang@supermarketku.com'],
            [
                'name'       => 'Gudang SupermarketKu',
                'password'   => Hash::make(env('SEEDER_USER_PASSWORD', 'password')),
                'role_id'    => $gudang->id,
            ]
        );
    }
}
