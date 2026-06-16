<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::withCount('variants')->get();
        $variants = ProductVariant::with('product')->latest()->get();
        
        return view('inventory.index', compact('products', 'variants'));
    }
}
