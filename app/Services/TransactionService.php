<?php

namespace App\Services;

use App\Helpers\PromoHelper;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionDetail;
use App\Models\Payment;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * @param array $cart Items: [['id' => int, 'quantity' => int, 'price' => int, 'subtotal' => int], ...]
     * @param array $data Keys: customer_id, method, paid_amount, discount, tax, card_bank, trace_number
     * @return Transaction
     * @throws \Exception
     */
    public static function processCheckout(array $cart, array $data): Transaction
    {
        if (empty($cart)) {
            throw new \Exception('Keranjang masih kosong.');
        }

        $total = collect($cart)->sum('subtotal');
        $discount = $data['discount'] ?? 0;
        $tax = $data['tax'] ?? 0;
        $grandTotal = max($total - $discount + $tax, 0);

        if ($discount > $total || $discount < 0) {
            throw new \InvalidArgumentException('Invalid discount amount');
        }
        if ($tax < 0) {
            throw new \InvalidArgumentException('Invalid tax amount');
        }

        $paidAmount = $data['method'] === 'cash'
            ? ($data['paid_amount'] ?? $grandTotal)
            : $grandTotal;

        if ($data['method'] === 'cash' && $paidAmount < $grandTotal) {
            throw new \Exception('Uang tunai kurang.');
        }

        return DB::transaction(function () use ($cart, $data, $total, $discount, $tax, $grandTotal, $paidAmount) {
            $transaction = Transaction::create([
                'invoice_number' => InvoiceService::generateTransactionInvoice(),
                'user_id' => auth()->id(),
                'customer_id' => $data['customer_id'] ?? null,
                'total_price' => $total,
                'discount' => $discount,
                'tax' => $tax,
                'grand_total' => $grandTotal,
            ]);

            foreach ($cart as $item) {
                $product = Product::where('id', $item['product_id'] ?? $item['id'])->lockForUpdate()->first();
                if (!$product) {
                    throw new \Exception('Produk tidak ditemukan.');
                }

                $price = PromoHelper::getFinalPrice($product);
                if (empty($price) || $price <= 0) {
                    $price = $product->selling_price;
                }
                $subtotal = $price * $item['quantity'];

                if ($product->stock - $item['quantity'] < 0) {
                    throw new \RuntimeException("Stok produk {$product->name} tidak mencukupi");
                }
                $product->decrement('stock', $item['quantity']);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'description' => 'Penjualan invoice ' . $transaction->invoice_number,
                ]);
            }

            Payment::create([
                'transaction_id' => $transaction->id,
                'method' => $data['method'],
                'paid_amount' => $paidAmount,
                'change_amount' => $data['method'] === 'cash' ? ($paidAmount - $grandTotal) : 0,
                'card_bank' => $data['card_bank'] ?? null,
                'trace_number' => $data['trace_number'] ?? null,
            ]);

            return $transaction;
        });
    }
}
