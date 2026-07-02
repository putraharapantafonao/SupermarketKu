<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
        'category_id' => 1,
        'supplier_id' => 1,
        'barcode' => '899100000001',
        'name' => 'Indomie Goreng',
        'purchase_price' => 2500,
        'selling_price' => 3500,
        'stock' => 100,
        'minimum_stock' => 10,
        'expired_date' => '2027-12-31',
        ]);

        Product::create([
            'category_id' => 2,
            'supplier_id' => 1,
            'barcode' => '899100000002',
            'name' => 'Aqua Botol 600ml',
            'purchase_price' => 3000,
            'selling_price' => 4000,
            'stock' => 80,
            'minimum_stock' => 10,
            'expired_date' => '2027-10-20',
        ]);

        Product::create([
            'category_id' => 3,
            'supplier_id' => 2,
            'barcode' => '899100000003',
            'name' => 'Beras Ramos 5Kg',
            'purchase_price' => 65000,
            'selling_price' => 75000,
            'stock' => 30,
            'minimum_stock' => 5,
            'expired_date' => '2027-06-30',
        ]);
    }
}
