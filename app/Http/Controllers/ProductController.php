<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'supplier'])
            ->when($request->filled('search') && strlen($request->search) >= 3, function ($query) use ($request) {
                $query->whereFullText(['name', 'barcode'], $request->search, ['mode' => 'BOOLEAN']);
            })
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'name' => 'required|string|max:255',
            'purchase_price' => 'required|integer|min:0',
            'selling_price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expired_date' => 'nullable|date',
        ]);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'barcode' => 'required|string|max:255|unique:products,barcode',
            'name' => 'required|string|max:255',
            'purchase_price' => 'required|integer|min:0',
            'selling_price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expired_date' => 'nullable|date',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'barcode' => 'required|string|max:255|unique:products,barcode,' . $product->id,
            'name' => 'required|string|max:255',
            'purchase_price' => 'required|integer|min:0',
            'selling_price' => 'required|integer|min:0|gte:purchase_price',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expired_date' => 'nullable|date',
        ]);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'barcode' => 'required|string|max:255|unique:products,barcode,' . $product->id,
            'name' => 'required|string|max:255',
            'purchase_price' => 'required|integer|min:0',
            'selling_price' => 'required|integer|min:0|gte:purchase_price',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expired_date' => 'nullable|date',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
