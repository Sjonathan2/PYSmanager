<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // --- Stats Cards ---
        $income = Transaction::where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $expense = Transaction::where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $profit = $income - $expense;

        $totalSold = Transaction::where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('quantity');

        $totalProducts = Product::count();
        $totalVariants = ProductVariant::count();
        $totalStock = ProductVariant::sum('stock');
        $totalCustomers = Customer::count();
        $pendingOrders = ProductionOrder::where('status', '!=', 'delivered')->count();

        $lowStockThreshold = 5;
        $lowStockCount = ProductVariant::where('stock', '<=', $lowStockThreshold)->count();

        $lowStockItems = ProductVariant::with('product')
            ->where('stock', '<=', $lowStockThreshold)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // --- Chart Data: 6 bulan terakhir Income vs Expense ---
        $months = [];
        $chartIncome = [];
        $chartExpense = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $months[] = $date->translatedFormat('M Y');

            $chartIncome[] = (int) Transaction::where('type', 'income')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');

            $chartExpense[] = (int) Transaction::where('type', 'expense')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');
        }

        // --- Top Selling Products ---
        $topProducts = Transaction::where('type', 'income')
            ->whereNotNull('product_variant_id')
            ->with('productVariant.product')
            ->select('product_variant_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(amount) as total_revenue'))
            ->groupBy('product_variant_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // --- Recent Transactions ---
        $recentTransactions = Transaction::orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // --- Recent Production Orders ---
        $recentOrders = ProductionOrder::with('customer', 'product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'income', 'expense', 'profit', 'totalSold',
            'totalProducts', 'totalVariants', 'totalStock', 'totalCustomers', 'pendingOrders',
            'lowStockCount', 'lowStockItems',
            'months', 'chartIncome', 'chartExpense',
            'topProducts', 'recentTransactions', 'recentOrders'
        ));
    }
}
