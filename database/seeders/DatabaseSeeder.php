<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Master data (no dependencies)
            RoleSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,

            // Depends on RoleSeeder
            UserSeeder::class,

            // Depends on CategorySeeder + SupplierSeeder
            ProductSeeder::class,

            // Standalone data
            CustomerSeeder::class,

            // Transactions (depends on User, Customer, Product)
            TransactionSeeder::class,
        ]);
    }
}
