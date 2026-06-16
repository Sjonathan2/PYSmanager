<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // --- Summary Stats ---
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $totalProfit = $totalIncome - $totalExpense;

        $monthIncome = Transaction::where('type', 'income')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');
        $monthExpense = Transaction::where('type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');
        $monthProfit = $monthIncome - $monthExpense;

        // --- Chart: 6 bulan terakhir ---
        $months = [];
        $chartIncome = [];
        $chartExpense = [];
        $chartProfit = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $months[] = $date->translatedFormat('M Y');

            $inc = (int) Transaction::where('type', 'income')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');
            $exp = (int) Transaction::where('type', 'expense')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');

            $chartIncome[] = $inc;
            $chartExpense[] = $exp;
            $chartProfit[] = $inc - $exp;
        }

        // --- Profit per Product Variant ---
        $productProfits = Transaction::where('type', 'income')
            ->whereNotNull('product_variant_id')
            ->with('productVariant.product')
            ->select(
                'product_variant_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(amount) as total_revenue')
            )
            ->groupBy('product_variant_id')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(function ($item) {
                $variant = $item->productVariant;
                $avgCost = $variant && $variant->product
                    ? $variant->price * 0.45 // Estimasi HPP 45% dari harga jual
                    : 0;
                $totalCost = $avgCost * $item->total_sold;
                $profit = $item->total_revenue - $totalCost;
                $margin = $item->total_revenue > 0
                    ? round(($profit / $item->total_revenue) * 100, 1)
                    : 0;

                return [
                    'product_name' => $variant?->product?->name ?? '-',
                    'sku' => $variant?->sku ?? '-',
                    'size' => $variant?->size ?? '-',
                    'color' => $variant?->color ?? '-',
                    'price' => $variant?->price ?? 0,
                    'hpp_est' => $avgCost,
                    'total_sold' => $item->total_sold,
                    'total_revenue' => $item->total_revenue,
                    'total_cost' => $totalCost,
                    'profit' => $profit,
                    'margin' => $margin,
                ];
            });

        // --- Expense Breakdown ---
        $expenseBreakdown = Transaction::where('type', 'expense')
            ->select('description', DB::raw('SUM(amount) as total'))
            ->groupBy('description')
            ->orderByDesc('total')
            ->get();

        // --- Recent Transactions ---
        $transactions = Transaction::latest('transaction_date')->latest()->take(20)->get();

        return view('finance.index', compact(
            'totalIncome', 'totalExpense', 'totalProfit',
            'monthIncome', 'monthExpense', 'monthProfit',
            'months', 'chartIncome', 'chartExpense', 'chartProfit',
            'productProfits', 'expenseBreakdown', 'transactions'
        ));
    }
}
