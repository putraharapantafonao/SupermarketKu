<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Services\InvoiceService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'customer', 'payment'])
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'customer', 'details.product', 'payment']);

        return view('transactions.show', compact('transaction'));
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['user', 'customer', 'details.product', 'payment']);

        return view('transactions.receipt', compact('transaction'));
    }

    /**
     * Memproses penyimpanan transaksi baru dari POS / Kasir
     */
    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required|in:cash,qris,transfer,ewallet,debit',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'customer_id' => 'nullable|exists:customers,id',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);

        // Convert request format to cart format
        $cart = [];
        $products = Product::whereIn('id', $request->product_id)->get()->keyBy('id');

        foreach ($request->product_id as $index => $productId) {
            $product = $products->get($productId);
            if (!$product) {
                return redirect()->back()->with('error', "Produk dengan ID {$productId} tidak ditemukan!");
            }

            $qty = $request->quantity[$index];
            if ($product->stock < $qty) {
                return redirect()->back()->with('error', "Stok untuk produk {$product->name} tidak mencukupi!");
            }

            $cart[$productId] = [
                'id' => (int) $productId,
                'quantity' => (int) $qty,
                'price' => $product->selling_price,
                'subtotal' => $product->selling_price * $qty,
            ];
        }

        try {
            $transaction = TransactionService::processCheckout($cart, [
                'customer_id' => $request->customer_id,
                'method' => $request->method,
                'paid_amount' => $request->paid_amount,
                'discount' => $request->discount ?? 0,
                'tax' => $request->tax ?? 0,
                'card_bank' => $request->card_bank,
                'trace_number' => $request->trace_number,
            ]);

            return redirect()->route('transactions.show', $transaction->id)
                ->with('success', 'Transaksi Berhasil Disimpan!')
                ->with('print_on_load', true);

        } catch (\Exception $e) {
            \Log::error('Transaction store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem. Silakan hubungi administrator.');
        }
    }
}
