<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Payment;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $kasirUsers = User::whereHas('role', fn($q) => $q->where('name', 'Kasir'))->get();
        if ($kasirUsers->isEmpty()) {
            $kasirUsers = User::all();
        }

        $customers = Customer::all();
        $products = Product::where('stock', '>', 0)->get();

        if ($products->isEmpty()) {
            $this->command?->warn('No products with stock found. Run ProductSeeder first.');
            return;
        }

        $dayCount = 30;
        $startDate = now()->subDays($dayCount - 1)->startOfDay();
        $dailyData = $this->generateDailyData($dayCount, $startDate, $kasirUsers, $customers, $products);

        $bar = $this->command ? $this->command->getOutput()->createProgressBar(count($dailyData)) : null;

        foreach ($dailyData as $dayData) {
            DB::transaction(function () use ($dayData) {
                foreach ($dayData['transactions'] as $tx) {
                    $this->createTransaction($tx, $dayData['products']);
                }
            });
            if ($bar) {
                $bar->advance();
            }
        }

        if ($bar) {
            $bar->finish();
            $this->command?->newLine();
        }

        $total = Transaction::count();
        $totalRevenue = Transaction::sum('grand_total');
        $this->command?->info("Created {$total} transactions with total revenue Rp " . number_format($totalRevenue, 0, ',', '.'));
    }

    private function generateDailyData(int $dayCount, \Carbon\Carbon $startDate, $kasirUsers, $customers, $products): array
    {
        $dailyData = [];
        $monthlyCounters = [];

        for ($day = 0; $day < $dayCount; $day++) {
            $date = $startDate->copy()->addDays($day);
            $dayOfWeek = $date->dayOfWeek;

            $txCount = match(true) {
                $dayOfWeek === 0 => random_int(12, 20),   // Minggu
                $dayOfWeek === 6 => random_int(15, 25),   // Sabtu
                $dayOfWeek >= 1 && $dayOfWeek <= 5 => random_int(10, 18), // Weekday
                default => 12,
            };

            $monthKey = $date->format('Ym');
            $monthlyCounters[$monthKey] = $monthlyCounters[$monthKey] ?? 0;

            $transactions = [];
            for ($i = 0; $i < $txCount; $i++) {
                $monthlyCounters[$monthKey]++;
                $dailyCounters = $monthlyCounters[$monthKey];

                $hour = $this->pickShoppingHour();
                $minute = random_int(0, 59);
                $createdAt = $date->copy()->setTime($hour, $minute);

                $itemCount = random_int(1, 8);
                $selectedProducts = $this->pickProducts($products, $itemCount);

                $totalPrice = 0;
                $details = [];
                foreach ($selectedProducts as [$product, $qty]) {
                    $lineTotal = $product->selling_price * $qty;
                    $totalPrice += $lineTotal;
                    $details[] = [
                        'product' => $product,
                        'quantity' => $qty,
                        'price' => $product->selling_price,
                        'subtotal' => $lineTotal,
                    ];
                }

                $hasDiscount = random_int(1, 100) <= 15;
                $discount = $hasDiscount ? (int) ($totalPrice * 0.1) : 0;
                $tax = (int) (($totalPrice - $discount) * 0.11);
                $grandTotal = $totalPrice - $discount + $tax;

                $useCustomer = random_int(1, 100) <= 60;
                $customerId = $useCustomer ? $customers->random()->id : null;

                $kasir = $kasirUsers->random();

                $method = $this->pickPaymentMethod();
                $paidAmount = $grandTotal;
                $changeAmount = 0;

                if ($method === 'cash') {
                    $overpayOptions = [10000, 20000, 50000, 100000, round($grandTotal / 10000, 0) * 10000 + 10000];
                    $paidAmount = $grandTotal + $overpayOptions[array_rand($overpayOptions)];
                    $changeAmount = $paidAmount - $grandTotal;
                }

                $invoiceNumber = 'INV-' . $createdAt->format('Ymd') . '-' . str_pad($dailyCounters, 4, '0', STR_PAD_LEFT);

                $transactions[] = [
                    'invoice_number' => $invoiceNumber,
                    'user_id' => $kasir->id,
                    'customer_id' => $customerId,
                    'total_price' => $totalPrice,
                    'discount' => $discount,
                    'tax' => $tax,
                    'grand_total' => $grandTotal,
                    'status' => 'completed',
                    'details' => $details,
                    'payment' => [
                        'method' => $method,
                        'paid_amount' => $paidAmount,
                        'change_amount' => $changeAmount,
                        'card_bank' => $method === 'debit' ? ['BRI', 'BNI', 'BCA', 'Mandiri'][array_rand(['BRI', 'BNI', 'BCA', 'Mandiri'])] : null,
                        'trace_number' => $method === 'debit' ? strtoupper(bin2hex(random_bytes(4))) : null,
                    ],
                    'created_at' => $createdAt,
                    'kasir_name' => $kasir->name,
                ];
            }

            $dailyData[] = [
                'date' => $date,
                'transactions' => $transactions,
                'products' => $products,
            ];
        }

        return $dailyData;
    }

    private function createTransaction(array $tx, $products): void
    {
        $transaction = Transaction::create([
            'invoice_number' => $tx['invoice_number'],
            'user_id' => $tx['user_id'],
            'customer_id' => $tx['customer_id'],
            'total_price' => $tx['total_price'],
            'discount' => $tx['discount'],
            'tax' => $tx['tax'],
            'grand_total' => $tx['grand_total'],
            'created_at' => $tx['created_at'],
            'updated_at' => $tx['created_at'],
        ]);

        foreach ($tx['details'] as $detail) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $detail['product']->id,
                'quantity' => $detail['quantity'],
                'price' => $detail['price'],
                'subtotal' => $detail['subtotal'],
                'created_at' => $tx['created_at'],
                'updated_at' => $tx['created_at'],
            ]);

            $product = $detail['product'];
            $newStock = max(0, $product->stock - $detail['quantity']);
            $product->update(['stock' => $newStock]);

            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $detail['quantity'],
                'description' => 'Penjualan ' . $tx['invoice_number'],
                'created_at' => $tx['created_at'],
                'updated_at' => $tx['created_at'],
            ]);
        }

        Payment::create([
            'transaction_id' => $transaction->id,
            'method' => $tx['payment']['method'],
            'paid_amount' => $tx['payment']['paid_amount'],
            'change_amount' => $tx['payment']['change_amount'],
            'card_bank' => $tx['payment']['card_bank'],
            'trace_number' => $tx['payment']['trace_number'],
            'created_at' => $tx['created_at'],
            'updated_at' => $tx['created_at'],
        ]);
    }

    private function pickShoppingHour(): int
    {
        $weights = [
            6 => 2,  7 => 5,  8 => 8,  9 => 10, 10 => 12,
            11 => 14, 12 => 10, 13 => 8, 14 => 7, 15 => 6,
            16 => 5, 17 => 4, 18 => 3, 19 => 2, 20 => 1,
        ];

        $total = array_sum($weights);
        $rand = random_int(1, $total);

        $cumulative = 0;
        foreach ($weights as $hour => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $hour;
            }
        }

        return 10;
    }

    private function pickProducts($products, int $count): array
    {
        $selected = [];
        $available = $products->filter(fn($p) => $p->stock > 0);

        if ($available->isEmpty()) {
            return [];
        }

        for ($i = 0; $i < $count; $i++) {
            $product = $this->weightedPick($available);
            if ($product && $product->stock > 0) {
                $maxQty = min($product->stock, $this->pickQuantity($product));
                $selected[] = [$product, $maxQty];
            }
        }

        return $selected;
    }

    private function weightedPick($products)
    {
        $priceBuckets = [
            'cheap' => $products->filter(fn($p) => $p->selling_price <= 5000),
            'medium' => $products->filter(fn($p) => $p->selling_price > 5000 && $p->selling_price <= 20000),
            'expensive' => $products->filter(fn($p) => $p->selling_price > 20000 && $p->selling_price <= 50000),
            'premium' => $products->filter(fn($p) => $p->selling_price > 50000),
        ];

        $weights = [
            'cheap' => 50,
            'medium' => 30,
            'expensive' => 15,
            'premium' => 5,
        ];

        $rand = random_int(1, 100);
        $cumulative = 0;
        foreach ($weights as $bucket => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                $bucketProducts = $priceBuckets[$bucket]->filter(fn($p) => $p->stock > 0);
                return $bucketProducts->isNotEmpty() ? $bucketProducts->random() : $products->random();
            }
        }

        return $products->random();
    }

    private function pickQuantity($product): int
    {
        return match(true) {
            $product->selling_price <= 3000 => random_int(2, 5),
            $product->selling_price <= 10000 => random_int(1, 3),
            $product->selling_price <= 30000 => random_int(1, 2),
            default => 1,
        };
    }

    private function pickPaymentMethod(): string
    {
        $methods = [
            'cash' => 60,
            'qris' => 18,
            'transfer' => 12,
            'ewallet' => 6,
            'debit' => 4,
        ];

        $rand = random_int(1, 100);
        $cumulative = 0;
        foreach ($methods as $method => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $method;
            }
        }

        return 'cash';
    }
}
