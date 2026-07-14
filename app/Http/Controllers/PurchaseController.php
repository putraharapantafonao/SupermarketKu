<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'user'])
            ->latest()
            ->paginate(10);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $productIds = $request->input('product_id', []);
        $quantities = $request->input('quantity', []);
        $prices = $request->input('purchase_price', []);

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array|size:' . count($productIds),
            'quantity.*' => 'required|integer|min:1',
            'purchase_price' => 'required|array|size:' . count($productIds),
            'purchase_price.*' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $totalPrice = 0;

            foreach ($request->product_id as $index => $productId) {
                $totalPrice += $request->quantity[$index] * $request->purchase_price[$index];
            }

            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'purchase_code' => 'PO-' . date('YmdHis'),
                'total_price' => $totalPrice,
            ]);

            $products = Product::whereIn('id', $request->product_id)->get()->keyBy('id');

            foreach ($request->product_id as $index => $productId) {
                $product = $products->get($productId);
                if (!$product) {
                    throw new \Exception("Produk dengan ID {$productId} tidak ditemukan!");
                }

                $quantity = $request->quantity[$index];
                $purchasePrice = $request->purchase_price[$index];
                $subtotal = $quantity * $purchasePrice;

                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'purchase_price' => $purchasePrice,
                    'subtotal' => $subtotal,
                ]);

                $product->increment('stock', $quantity);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $quantity,
                    'description' => 'Pembelian stok kode ' . $purchase->purchase_code,
                ]);
            }
        });

        return redirect()->route('purchases.index')
            ->with('success', 'Pembelian stok berhasil disimpan.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'details.product']);

        return view('purchases.show', compact('purchase'));
    }
}
