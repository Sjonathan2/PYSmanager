<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|string|max:255|unique:product_variants,sku',
            'size' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        ProductVariant::create($validated);

        return redirect()->route('inventory.index')->with('success', 'Varian berhasil ditambahkan!');
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:product_variants,sku,' . $variant->id,
            'size' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $variant->update($validated);

        return redirect()->route('inventory.index')->with('success', 'Varian berhasil diupdate!');
    }

    public function destroy(ProductVariant $variant)
    {
        $variant->delete();

        return redirect()->route('inventory.index')->with('success', 'Varian berhasil dihapus!');
    }
}
