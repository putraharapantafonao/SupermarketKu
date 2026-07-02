<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Makanan', 'description' => 'Produk makanan ringan dan berat']);
        Category::create(['name' => 'Minuman', 'description' => 'Produk minuman']);
        Category::create(['name' => 'Sembako', 'description' => 'Kebutuhan pokok']);
        Category::create(['name' => 'Peralatan Rumah Tangga', 'description' => 'Kebutuhan rumah tangga']);
        Category::create(['name' => 'Kosmetik', 'description' => 'Produk kecantikan dan perawatan']);
    }
}
