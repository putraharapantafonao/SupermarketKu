<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('name')->get();

        return view('stock-opname.index', compact('products'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'real_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        $oldStock = $product->stock;
        $realStock = $request->real_stock;
        $difference = $realStock - $oldStock;

        if ($difference == 0) {
            return back()->with('error', 'Stok tidak berubah.');
        }

        $product->update([
            'stock' => $realStock,
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'type' => $difference > 0 ? 'in' : 'out',
            'quantity' => abs($difference),
            'description' => $request->description ?? 'Stock opname',
        ]);

        return back()->with('success', 'Stock opname berhasil diperbarui.');
    }
}
