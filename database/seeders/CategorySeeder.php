<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    protected array $categories = [
        ['name' => 'Makanan',               'description' => 'Makanan ringan, basah, dan siap saji'],
        ['name' => 'Minuman',                'description' => 'Minuman ringan, dingin, dan panas'],
        ['name' => 'Sembako',                'description' => 'Kebutuhan pokok: beras, minyak, gula, dll'],
        ['name' => 'Bumbu Masakan',          'description' => 'Bumbu instan, saus, kecap, penyedap rasa'],
        ['name' => 'Peralatan Rumah Tangga', 'description' => 'Sabun, deterjen, tisu, pel, dll'],
        ['name' => 'Produk Bayi',            'description' => 'Popok, susu, makanan bayi'],
        ['name' => 'Kecantikan',             'description' => 'Perawatan kulit, riasan, parfum'],
        ['name' => 'Obat & Kesehatan',       'description' => 'Vitamin, obat bebas, alat kesehatan'],
    ];

    public function run(): void
    {
        foreach ($this->categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
