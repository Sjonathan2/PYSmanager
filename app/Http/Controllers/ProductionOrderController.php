<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductionOrderController extends Controller
{
    public function index()
    {
        $orders = ProductionOrder::with('customer', 'product')
            ->latest('order_date')
            ->latest()
            ->get();
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('orders.index', compact('orders', 'customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'custom_color' => 'nullable|string|max:255',
            'order_date' => 'required|date',
            'deadline_date' => 'nullable|date|after_or_equal:order_date',
            'total_price' => 'required|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['po_number'] = 'PO-' . strtoupper(Str::random(4)) . '-' . now()->format('His');
        $validated['status'] = 'pending';

        ProductionOrder::create($validated);

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    public function update(Request $request, ProductionOrder $order)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'custom_color' => 'nullable|string|max:255',
            'order_date' => 'required|date',
            'deadline_date' => 'nullable|date',
            'total_price' => 'required|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil diupdate!');
    }

    public function updateStatus(Request $request, ProductionOrder $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,cutting,sewing,finishing,ready,delivered',
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')->with('success', 'Status pesanan berhasil diubah!');
    }

    public function destroy(ProductionOrder $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus!');
    }
}
