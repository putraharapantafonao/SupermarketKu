<?php

namespace App\Http\Controllers;

use App\Helpers\PromoHelper;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('stock', '>', 0)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('barcode', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->get();

        $cart = session()->get('cart', []);

        return view('pos.index', compact('products', 'cart'));
    }

    private function addProductToCart(Product $product)
    {
        if ($product->stock <= 0) {
            return [
                'status' => false,
                'message' => 'Stok produk habis.',
            ];
        }

        $cart = session()->get('cart', []);

        // Mengambil harga dari PromoHelper
        $price = PromoHelper::getFinalPrice($product);

        // AMAN: Jika PromoHelper mengembalikan null/0, gunakan selling_price bawaan produk
        if (empty($price) || $price <= 0) {
            $price = $product->selling_price;
        }

        if (isset($cart[$product->id])) {
            if ($cart[$product->id]['quantity'] < $product->stock) {
                $cart[$product->id]['quantity']++;
                $cart[$product->id]['subtotal'] =
                    $cart[$product->id]['quantity'] * $cart[$product->id]['price'];
            } else {
                return [
                    'status' => false,
                    'message' => 'Jumlah melebihi stok tersedia.',
                ];
            }
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price, // Harga dijamin terisi angka valid
                'quantity' => 1,
                'subtotal' => $price,
            ];
        }

        session()->put('cart', $cart);

        return [
            'status' => true,
            'message' => 'Produk berhasil ditambahkan.',
        ];
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $result = $this->addProductToCart($product);

        return redirect()->route('pos.index')->with(
            $result['status'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function scanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $product = Product::where('barcode', $request->barcode)->first();

        if (!$product) {
            return redirect()->route('pos.index')
                ->with('error', 'Produk dengan barcode tersebut tidak ditemukan.');
        }

        $result = $this->addProductToCart($product);

        return redirect()->route('pos.index')->with(
            $result['status'] ? 'success' : 'error',
            $result['status'] ? 'Produk berhasil ditambahkan dari barcode.' : $result['message']
        );
    }

    public function increaseQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = session()->get('cart', []);
        $product = Product::findOrFail($request->product_id);

        if (!isset($cart[$product->id])) {
            return redirect()->route('pos.index')
                ->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        if ($cart[$product->id]['quantity'] >= $product->stock) {
            return redirect()->route('pos.index')
                ->with('error', 'Jumlah melebihi stok tersedia.');
        }

        $cart[$product->id]['quantity']++;
        $cart[$product->id]['subtotal'] =
            $cart[$product->id]['quantity'] * $cart[$product->id]['price'];

        session()->put('cart', $cart);

        return redirect()->route('pos.index')
            ->with('success', 'Jumlah produk berhasil ditambah.');
    }

    public function decreaseQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (!isset($cart[$productId])) {
            return redirect()->route('pos.index')
                ->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        if ($cart[$productId]['quantity'] <= 1) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity']--;
            $cart[$productId]['subtotal'] =
                $cart[$productId]['quantity'] * $cart[$productId]['price'];
        }

        session()->put('cart', $cart);

        return redirect()->route('pos.index')
            ->with('success', 'Jumlah produk berhasil dikurangi.');
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        $cart = session()->get('cart', []);

        unset($cart[$request->product_id]);

        session()->put('cart', $cart);

        return redirect()->route('pos.index')
            ->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function clearCart()
    {
        session()->forget('cart');

        return redirect()->route('pos.index')
            ->with('success', 'Keranjang berhasil dikosongkan.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('pos.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $customers = Customer::all();

        return view('pos.checkout', compact('cart', 'customers'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'method' => 'required|in:cash,qris,transfer,ewallet,debit',
            'paid_amount' => 'nullable|integer|min:0',
            'discount' => 'nullable|integer|min:0',
            'card_bank' => 'nullable|string',
            'trace_number' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('pos.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $total = collect($cart)->sum('subtotal');
        $discount = $request->discount ?? 0;
        $tax = 0;
        $grandTotal = max($total - $discount + $tax, 0);

        if ($discount > $total) {
            return back()->with('error', 'Diskon tidak boleh lebih besar dari total belanja.');
        }

        //  KODE PERBAIKAN AMAN:
$paidAmount = $request->method === 'cash'
    ? ($request->filled('paid_amount') ? $request->paid_amount : $grandTotal)
    : $grandTotal;
        if ($request->method === 'cash' && $paidAmount < $grandTotal) {
            return back()->with('error', 'Uang tunai kurang.');
        }

        try {
            $transactionId = null;

            // FIX PARSEERROR: Mengganti '[' menjadi '{' yang valid untuk block closure PHP
            DB::transaction(function () use ($request, $cart, $total, $discount, $tax, $grandTotal, $paidAmount, &$transactionId) {

                $transaction = Transaction::create([
                    'invoice_number' => 'INV-' . date('YmdHis') . '-' . str_pad(Transaction::count() + 1, 4, '0', STR_PAD_LEFT),
                    'user_id' => auth()->id(),
                    'customer_id' => $request->customer_id,
                    'total_price' => $total,
                    'discount' => $discount,
                    'tax' => $tax,
                    'grand_total' => $grandTotal,
                ]);

                $transactionId = $transaction->id;

                foreach ($cart as $item) {
                    $product = Product::findOrFail($item['id']);

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception('Stok produk ' . $product->name . ' tidak cukup.');
                    }

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $product->decrement('stock', $item['quantity']);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'out',
                        'quantity' => $item['quantity'],
                        'description' => 'Penjualan invoice ' . $transaction->invoice_number,
                    ]);
                }

                Payment::create([
                    'transaction_id' => $transaction->id,
                    'method' => $request->method,
                    'paid_amount' => $paidAmount,
                    'change_amount' => $request->method === 'cash' ? ($paidAmount - $grandTotal) : 0,
                    'card_bank' => $request->card_bank,
                    'trace_number' => $request->trace_number,
                ]);

                session()->forget('cart');
            });

            return redirect()->route('transactions.show', $transactionId)
                ->with('success', 'Transaksi berhasil disimpan.')
                ->with('print_on_load', true);

        } catch (\Exception $e) {
            return redirect()->route('pos.index')
                ->with('error', $e->getMessage());
        }
    }
}
