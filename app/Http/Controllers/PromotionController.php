<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('product')->latest()->paginate(10);
        return view('promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::all();
        return view('promotions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'type' => 'required|in:percent,nominal',
            'value' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Promotion::create($request->all());

        return redirect()->route('promotions.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::all();
        return view('promotions.edit', compact('promotion', 'products'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'type' => 'required|in:percent,nominal',
            'value' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $promotion->update($request->all());

        return redirect()->route('promotions.index')->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('promotions.index')->with('success', 'Promo berhasil dihapus.');
    }
}
