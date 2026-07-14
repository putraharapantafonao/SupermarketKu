<?php

namespace App\Http\Controllers;

use App\Helpers\PromoHelper;
use App\Models\Customer;
use App\Models\Product;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('stock', '>', 0)
            ->when($request->filled('search') && strlen($request->search) >= 3, function ($query) use ($request) {
                $query->whereFullText(['name', 'barcode'], $request->search, ['mode' => 'BOOLEAN']);
            })
            ->latest()
            ->get();

        $cart = session()->get('cart', []);

        $customers = Customer::orderBy('name')->get();

        return view('pos.index', compact('products', 'cart', 'customers'));
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
            'product_id' => 'required|exists:products,id',
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
            'product_id' => 'required|exists:products,id',
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
        $cart = session()->get('cart', []);

        $cartTotal = collect($cart)->sum('subtotal');

        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'method' => 'required|in:cash,qris,transfer,ewallet,debit',
            'paid_amount' => 'nullable|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:' . $cartTotal,
            'tax' => 'nullable|numeric|min:0|max:' . $cartTotal,
            'card_bank' => 'nullable|string',
            'trace_number' => 'nullable|string',
        ]);

        try {
            $transaction = TransactionService::processCheckout($cart, [
                'customer_id' => $request->customer_id,
                'method' => $request->method,
                'paid_amount' => $request->paid_amount,
                'discount' => $request->discount ?? 0,
                'tax' => 0,
                'card_bank' => $request->card_bank,
                'trace_number' => $request->trace_number,
            ]);

            session()->forget('cart');

            return redirect()->route('transactions.show', $transaction->id)
                ->with('success', 'Transaksi berhasil disimpan.')
                ->with('print_on_load', true);

        } catch (\Exception $e) {
            \Log::error('POS checkout error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('pos.index')
                ->with('error', 'Terjadi kesalahan sistem. Silakan hubungi administrator.');
        }
    }
}
