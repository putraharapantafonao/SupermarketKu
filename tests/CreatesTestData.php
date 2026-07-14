<?php

namespace Tests;

use App\Models\{
    Role,
    User,
    Category,
    Supplier,
    Customer,
    Product,
    Transaction,
    TransactionDetail,
    Payment,
    StockMovement,
};

trait CreatesTestData
{
    protected function createRole(string $name = 'Kasir'): Role
    {
        return Role::firstOrCreate(['name' => $name]);
    }

    protected function createUser(string $roleName = 'Kasir'): User
    {
        $role = $this->createRole($roleName);

        return User::factory()->create(['role_id' => $role->id]);
    }

    protected function createCategory(array $attrs = []): Category
    {
        return Category::factory()->create($attrs);
    }

    protected function createSupplier(array $attrs = []): Supplier
    {
        return Supplier::factory()->create($attrs);
    }

    protected function createCustomer(array $attrs = []): Customer
    {
        return Customer::factory()->create($attrs);
    }

    protected function createProduct(array $attrs = []): Product
    {
        if (!isset($attrs['category_id'])) {
            $attrs['category_id'] = $this->createCategory()->id;
        }
        if (!isset($attrs['supplier_id'])) {
            $attrs['supplier_id'] = $this->createSupplier()->id;
        }

        return Product::factory()->create($attrs);
    }

    protected function createTransaction(array $attrs = []): Transaction
    {
        if (!isset($attrs['user_id'])) {
            $attrs['user_id'] = $this->createUser()->id;
        }

        return Transaction::factory()->create($attrs);
    }

    protected function createTransactionWithDetail(array $transactionAttrs = [], array $detailAttrs = []): Transaction
    {
        $transaction = $this->createTransaction($transactionAttrs);
        $product = $this->createProduct();
        $quantity = $detailAttrs['quantity'] ?? 2;
        $price = $detailAttrs['price'] ?? $product->selling_price;

        TransactionDetail::factory()->create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $price * $quantity,
        ]);

        Payment::factory()->create([
            'transaction_id' => $transaction->id,
            'method' => 'cash',
            'paid_amount' => $price * $quantity,
        ]);

        return $transaction->load(['details', 'payment', 'user', 'customer']);
    }

    protected function seedRoles(): void
    {
        Role::firstOrCreate(['name' => 'Owner']);
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Kasir']);
        Role::firstOrCreate(['name' => 'Gudang']);
    }
}
