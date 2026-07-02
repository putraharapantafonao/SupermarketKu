<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
        'name' => 'PT Sumber Rezeki',
        'phone' => '081234567890',
        'address' => 'Lhokseumawe',
        ]);

        Supplier::create([
            'name' => 'CV Maju Jaya',
            'phone' => '082345678901',
            'address' => 'Aceh Utara',
        ]);
    }
}
