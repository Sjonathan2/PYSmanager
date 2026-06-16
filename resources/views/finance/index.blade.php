<x-layouts.app title="Keuangan">
    @push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    @endpush

    <!-- Header -->
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Keuangan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Analisis profit, arus kas, dan pengeluaran bisnis.</p>
        </div>
        <div class="flex gap-2" x-data="{ showAddIncome: false, showAddExpense: false }">
            <button @click="showAddIncome = true" class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-4 py-2 text-sm font-black border-2 border-black dark:border-gray-600 shadow-[2px_2px_0px_rgba(0,0,0,1)] dark:shadow-[2px_2px_0px_rgba(107,114,128,1)] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all">
                + Pemasukan
            </button>
            <button @click="showAddExpense = true" class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-2 text-sm font-black border-2 border-black dark:border-gray-600 shadow-[2px_2px_0px_rgba(0,0,0,1)] dark:shadow-[2px_2px_0px_rgba(107,114,128,1)] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all">
                - Pengeluaran
            </button>

            <!-- MODAL: Tambah Pemasukan -->
            <div x-show="showAddIncome" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
                <div @click.away="showAddIncome = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[420px] max-w-[90vw]">
                    <h3 class="text-lg font-black mb-4 text-green-700 dark:text-green-400 uppercase">TAMBAH PEMASUKAN</h3>
                    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="type" value="income">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Deskripsi *</label>
                            <input type="text" name="description" required placeholder="Contoh: Penjualan Jaket Biker via Shopee" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Jumlah (Rp) *</label>
                                <input type="number" name="amount" required min="1000" placeholder="500000" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-bold">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Jumlah Item</label>
                                <input type="number" name="quantity" min="1" placeholder="1" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Tanggal *</label>
                            <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button type="submit" class="flex-1 bg-green-500 text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">SIMPAN</button>
                            <button type="button" @click="showAddIncome = false" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">BATAL</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL: Tambah Pengeluaran -->
            <div x-show="showAddExpense" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
                <div @click.away="showAddExpense = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[420px] max-w-[90vw]">
                    <h3 class="text-lg font-black mb-4 text-red-700 dark:text-red-400 uppercase">TAMBAH PENGELUARAN</h3>
                    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="type" value="expense">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Deskripsi *</label>
                            <input type="text" name="description" required placeholder="Contoh: Beli Bahan Kulit, Bayar Listrik" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Jumlah (Rp) *</label>
                            <input type="number" name="amount" required min="1000" placeholder="250000" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Tanggal *</label>
                            <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button type="submit" class="flex-1 bg-red-500 text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">SIMPAN</button>
                            <button type="button" @click="showAddExpense = false" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">BATAL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 border-2 border-green-600 text-green-800 dark:text-green-300 text-sm font-bold" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        {{ session('success') }}
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
        <div class="bg-white dark:bg-gray-900 p-4 border-2 border-black dark:border-gray-700 shadow-[3px_3px_0px_rgba(0,0,0,1)] dark:shadow-[3px_3px_0px_rgba(107,114,128,1)]">
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Total Pemasukan</div>
            <div class="text-lg font-black text-green-600 dark:text-green-400">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            <div class="text-[10px] text-gray-400 mt-1">Bulan ini: Rp {{ number_format($monthIncome, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white dark:bg-gray-900 p-4 border-2 border-black dark:border-gray-700 shadow-[3px_3px_0px_rgba(0,0,0,1)] dark:shadow-[3px_3px_0px_rgba(107,114,128,1)]">
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Total Pengeluaran</div>
            <div class="text-lg font-black text-red-500 dark:text-red-400">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            <div class="text-[10px] text-gray-400 mt-1">Bulan ini: Rp {{ number_format($monthExpense, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white dark:bg-gray-900 p-4 border-2 border-black dark:border-gray-700 shadow-[3px_3px_0px_rgba(0,0,0,1)] dark:shadow-[3px_3px_0px_rgba(107,114,128,1)]">
            <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Profit Bersih</div>
            <div class="text-lg font-black {{ $totalProfit >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-500' }}">Rp {{ number_format($totalProfit, 0, ',', '.') }}</div>
            <div class="text-[10px] text-gray-400 mt-1">Bulan ini: Rp {{ number_format($monthProfit, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)] mb-6">
        <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-4">Arus Kas (6 Bulan Terakhir)</h2>
        <div class="relative" style="height: 250px;">
            <canvas id="financeChart"></canvas>
        </div>
    </div>

    <!-- Profit per Product -->
    <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)] mb-6">
        <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-4">Analisis Profit per Produk</h2>
        <p class="text-[10px] text-gray-400 mb-3">Estimasi HPP = 45% dari harga jual (bahan kulit, furing, aksesoris, tenaga kerja)</p>
        
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b-2 border-black dark:border-gray-600">
                        <th class="text-left py-2 font-black uppercase text-[10px]">Produk</th>
                        <th class="text-left py-2 font-black uppercase text-[10px]">SKU</th>
                        <th class="text-center py-2 font-black uppercase text-[10px]">Size</th>
                        <th class="text-center py-2 font-black uppercase text-[10px]">Warna</th>
                        <th class="text-right py-2 font-black uppercase text-[10px]">Harga Jual</th>
                        <th class="text-right py-2 font-black uppercase text-[10px]">HPP Est.</th>
                        <th class="text-center py-2 font-black uppercase text-[10px]">Terjual</th>
                        <th class="text-right py-2 font-black uppercase text-[10px]">Revenue</th>
                        <th class="text-right py-2 font-black uppercase text-[10px]">Profit</th>
                        <th class="text-right py-2 font-black uppercase text-[10px]">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productProfits as $pf)
                    <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="py-2 font-bold text-gray-900 dark:text-white">{{ $pf['product_name'] }}</td>
                        <td class="py-2 text-gray-500 dark:text-gray-400 font-mono text-[10px]">{{ $pf['sku'] }}</td>
                        <td class="py-2 text-center text-gray-600 dark:text-gray-300">{{ $pf['size'] }}</td>
                        <td class="py-2 text-center text-gray-600 dark:text-gray-300">{{ $pf['color'] }}</td>
                        <td class="py-2 text-right text-gray-600 dark:text-gray-300">Rp {{ number_format($pf['price'], 0, ',', '.') }}</td>
                        <td class="py-2 text-right text-orange-500 dark:text-orange-400">Rp {{ number_format($pf['hpp_est'], 0, ',', '.') }}</td>
                        <td class="py-2 text-center font-black text-gray-900 dark:text-white">{{ $pf['total_sold'] }}</td>
                        <td class="py-2 text-right text-green-600 dark:text-green-400 font-bold">Rp {{ number_format($pf['total_revenue'], 0, ',', '.') }}</td>
                        <td class="py-2 text-right font-black {{ $pf['profit'] >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-500' }}">Rp {{ number_format($pf['profit'], 0, ',', '.') }}</td>
                        <td class="py-2 text-right">
                            <span class="inline-block px-2 py-0.5 text-[10px] font-bold border {{ $pf['margin'] >= 40 ? 'bg-green-100 text-green-700 border-green-300 dark:bg-green-900/30 dark:text-green-400 dark:border-green-700' : ($pf['margin'] >= 20 ? 'bg-yellow-100 text-yellow-700 border-yellow-300 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-700' : 'bg-red-100 text-red-700 border-red-300 dark:bg-red-900/30 dark:text-red-400 dark:border-red-700') }}">{{ $pf['margin'] }}%</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-8 text-center text-gray-500">Belum ada data penjualan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Expense Breakdown + All Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Expense Breakdown -->
        <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-4">Rincian Pengeluaran</h2>
            <div class="space-y-3">
                @forelse($expenseBreakdown as $eb)
                @php
                    $pct = $totalExpense > 0 ? round(($eb->total / $totalExpense) * 100, 1) : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $eb->description }}</span>
                        <span class="text-xs font-bold text-red-500">Rp {{ number_format($eb->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 border border-gray-300 dark:border-gray-600">
                        <div class="bg-red-400 dark:bg-red-600 h-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $pct }}% dari total pengeluaran</div>
                </div>
                @empty
                <div class="text-center text-xs text-gray-500 py-4">Belum ada pengeluaran.</div>
                @endforelse
            </div>
        </div>

        <!-- All Transactions -->
        <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-4">Semua Transaksi</h2>
            <div class="space-y-2 max-h-[400px] overflow-y-auto">
                @forelse($transactions as $trx)
                <div class="flex items-center justify-between p-2 border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <div class="min-w-0 flex-1">
                        <div class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $trx->description }}</div>
                        <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($trx->transaction_date)->translatedFormat('d M Y') }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="text-xs font-black {{ $trx->type === 'income' ? 'text-green-600' : 'text-red-500' }}">
                            {{ $trx->type === 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </div>
                        <form action="{{ route('transactions.destroy', $trx) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[9px] font-black text-red-400 hover:text-red-600 px-1">X</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center text-xs text-gray-500 py-4">Belum ada transaksi.</div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('financeChart');
            if (!ctx) return;

            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(75,85,99,0.3)' : 'rgba(0,0,0,0.08)';
            const textColor = isDark ? '#9CA3AF' : '#6B7280';

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: @json($chartIncome),
                            borderColor: '#22C55E',
                            backgroundColor: 'rgba(34,197,94,0.1)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#22C55E',
                        },
                        {
                            label: 'Pengeluaran',
                            data: @json($chartExpense),
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239,68,68,0.1)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#EF4444',
                        },
                        {
                            label: 'Profit',
                            data: @json($chartProfit),
                            borderColor: '#3B82F6',
                            backgroundColor: 'transparent',
                            borderDash: [5, 5],
                            tension: 0.3,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointBackgroundColor: '#3B82F6',
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

        document.addEventListener('darkModeToggled', function() {
            location.reload();
        });
    </script>
    @endpush
</x-layouts.app>
