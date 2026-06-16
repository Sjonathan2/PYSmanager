<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'chest_width' => 'nullable|numeric|min:0',
            'shoulder_width' => 'nullable|numeric|min:0',
            'arm_length' => 'nullable|numeric|min:0',
            'body_length' => 'nullable|numeric|min:0',
            'belly_circumference' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'chest_width' => 'nullable|numeric|min:0',
            'shoulder_width' => 'nullable|numeric|min:0',
            'arm_length' => 'nullable|numeric|min:0',
            'body_length' => 'nullable|numeric|min:0',
            'belly_circumference' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diupdate!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus!');
    }
}
