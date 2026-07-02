<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
        'name' => 'Owner SupermarketKu',
        'email' => 'owner@supermarketku.com',
        'password' => Hash::make('password'),
        'role_id' => 1,
        ]);

        User::create([
            'name' => 'Kasir SupermarketKu',
            'email' => 'kasir@supermarketku.com',
            'password' => Hash::make('password'),
            'role_id' => 3,
        ]);
    }
}
