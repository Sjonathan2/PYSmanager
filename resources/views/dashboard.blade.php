<x-layouts.app title="Dashboard">
    @push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    @endpush

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan bisnis PickYourStyle bulan {{ now()->translatedFormat('F Y') }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white dark:bg-gray-900 p-4 border-2 border-black dark:border-gray-700 shadow-[3px_3px_0px_rgba(0,0,0,1)] dark:shadow-[3px_3px_0px_rgba(107,114,128,1)]">
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Pemasukan</div>
            <div class="text-lg font-black text-green-600 dark:text-green-400">Rp {{ number_format($income, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white dark:bg-gray-900 p-4 border-2 border-black dark:border-gray-700 shadow-[3px_3px_0px_rgba(0,0,0,1)] dark:shadow-[3px_3px_0px_rgba(107,114,128,1)]">
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Pengeluaran</div>
            <div class="text-lg font-black text-red-500 dark:text-red-400">Rp {{ number_format($expense, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white dark:bg-gray-900 p-4 border-2 border-black dark:border-gray-700 shadow-[3px_3px_0px_rgba(0,0,0,1)] dark:shadow-[3px_3px_0px_rgba(107,114,128,1)]">
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Profit</div>
            <div class="text-lg font-black {{ $profit >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-500' }}">Rp {{ number_format($profit, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white dark:bg-gray-900 p-4 border-2 border-black dark:border-gray-700 shadow-[3px_3px_0px_rgba(0,0,0,1)] dark:shadow-[3px_3px_0px_rgba(107,114,128,1)]">
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Terjual</div>
            <div class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($totalSold, 0, ',', '.') }} <span class="text-sm">pcs</span></div>
        </div>
    </div>

    <!-- Second Row Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        <div class="bg-white dark:bg-gray-900 p-3 border-2 border-black dark:border-gray-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] dark:shadow-[2px_2px_0px_rgba(107,114,128,1)] text-center">
            <div class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalProducts }}</div>
            <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Produk</div>
        </div>
        <div class="bg-white dark:bg-gray-900 p-3 border-2 border-black dark:border-gray-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] dark:shadow-[2px_2px_0px_rgba(107,114,128,1)] text-center">
            <div class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalVariants }}</div>
            <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Varian</div>
        </div>
        <div class="bg-white dark:bg-gray-900 p-3 border-2 border-black dark:border-gray-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] dark:shadow-[2px_2px_0px_rgba(107,114,128,1)] text-center">
            <div class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalStock }}</div>
            <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Total Stok</div>
        </div>
        <div class="bg-white dark:bg-gray-900 p-3 border-2 border-black dark:border-gray-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] dark:shadow-[2px_2px_0px_rgba(107,114,128,1)] text-center">
            <div class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalCustomers }}</div>
            <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Pelanggan</div>
        </div>
        <div class="bg-white dark:bg-gray-900 p-3 border-2 border-black dark:border-gray-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] dark:shadow-[2px_2px_0px_rgba(107,114,128,1)] text-center">
            <div class="text-2xl font-black {{ $lowStockCount > 0 ? 'text-red-500' : 'text-green-600' }}">{{ $lowStockCount }}</div>
            <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Stok Menipis</div>
        </div>
    </div>

    <!-- Chart + Low Stock -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Chart: Income vs Expense -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-4">Pemasukan vs Pengeluaran (6 Bulan)</h2>
            <div class="relative" style="height: 260px;">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase">Stok Menipis!</h2>
                <a href="{{ route('inventory.index') }}" class="text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3 max-h-[220px] overflow-y-auto">
                @forelse($lowStockItems as $item)
                <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                    <div>
                        <div class="text-xs font-bold text-gray-900 dark:text-white">{{ $item->product->name }}</div>
                        <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ $item->size }} / {{ $item->color }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-black text-red-500">{{ $item->stock }}</div>
                        <div class="text-[9px] text-gray-500 uppercase font-bold">Sisa</div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-xs text-gray-500">Semua stok aman!</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Products + Recent Transactions + Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Selling Products -->
        <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-4">Produk Terlaris</h2>
            <div class="space-y-3">
                @forelse($topProducts as $idx => $tp)
                @php $variant = $tp->productVariant; @endphp
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 bg-blue-100 dark:bg-blue-900/30 border border-blue-300 dark:border-blue-700 flex items-center justify-center text-xs font-black text-blue-700 dark:text-blue-400">{{ $idx + 1 }}</div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $variant?->product?->name ?? '-' }}</div>
                        <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ $variant?->size }} / {{ $variant?->color }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-black text-gray-900 dark:text-white">{{ $tp->total_qty }} pcs</div>
                        <div class="text-[10px] text-green-600 dark:text-green-400">Rp {{ number_format($tp->total_revenue, 0, ',', '.') }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-xs text-gray-500 py-4">Belum ada penjualan.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase">Transaksi Terakhir</h2>
                <a href="{{ route('finance.index') }}" class="text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($recentTransactions as $trx)
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2 last:border-0 last:pb-0">
                    <div class="min-w-0 flex-1">
                        <div class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $trx->description }}</div>
                        <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($trx->transaction_date)->diffForHumans() }}</div>
                    </div>
                    <div class="text-xs font-black {{ $trx->type === 'income' ? 'text-green-600' : 'text-red-500' }}">
                        {{ $trx->type === 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </div>
                </div>
                @empty
                <div class="text-center text-xs text-gray-500 py-4">Belum ada transaksi.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase">Pesanan Terakhir</h2>
                <a href="{{ route('orders.index') }}" class="text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'cutting' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        'sewing' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                        'finishing' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                        'ready' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'delivered' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                    ];
                @endphp
                <div class="p-2 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-1">
                        <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400">{{ $order->po_number }}</div>
                        <span class="text-[9px] font-bold px-2 py-0.5 uppercase {{ $statusColors[$order->status] ?? '' }}">{{ $order->status }}</span>
                    </div>
                    <div class="text-xs font-bold text-gray-900 dark:text-white">{{ $order->customer?->name ?? '-' }}</div>
                    <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ $order->product?->name ?? '-' }}</div>
                </div>
                @empty
                <div class="text-center text-xs text-gray-500 py-4">Belum ada pesanan.</div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('incomeExpenseChart');
            if (!ctx) return;

            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(75,85,99,0.3)' : 'rgba(0,0,0,0.08)';
            const textColor = isDark ? '#9CA3AF' : '#6B7280';

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: @json($chartIncome),
                            backgroundColor: '#22C55E',
                            borderColor: '#16A34A',
                            borderWidth: 2,
                            borderRadius: 2,
                        },
                        {
                            label: 'Pengeluaran',
                            data: @json($chartExpense),
                            backgroundColor: '#EF4444',
                            borderColor: '#DC2626',
                            borderWidth: 2,
                            borderRadius: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { color: textColor, font: { weight: 'bold', size: 11 }, boxWidth: 12, padding: 15 }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.dataset.label + ': Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { weight: 'bold', size: 10 } }
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: {
                                color: textColor,
                                font: { size: 10 },
                                callback: function(v) { return 'Rp ' + (v/1000000).toFixed(1) + 'jt'; }
                            }
                        }
                    }
                }
            });
        });

        // Re-render chart on dark mode toggle
        document.addEventListener('darkModeToggled', function() {
            location.reload();
        });
    </script>
    @endpush
</x-layouts.app>
