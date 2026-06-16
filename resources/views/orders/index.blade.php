<x-layouts.app title="Pemesanan">
    <div x-data="orderManager()" class="space-y-4">
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pemesanan</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftar pesanan produksi custom jaket kulit.</p>
            </div>
            <button @click="showAdd = true" class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-2 text-sm font-black border-2 border-black shadow-[3px_3px_0px_rgba(0,0,0,1)] dark:shadow-[3px_3px_0px_rgba(107,114,128,1)] active:translate-x-[3px] active:translate-y-[3px] active:shadow-none transition-all">
                + Pesanan Baru
            </button>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 border-2 border-green-600 text-green-800 dark:text-green-300 text-sm font-bold" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('success') }}
        </div>
        @endif

        @php
            $statusColors = [
                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-300 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-700',
                'cutting' => 'bg-blue-100 text-blue-700 border-blue-300 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-700',
                'sewing' => 'bg-purple-100 text-purple-700 border-purple-300 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-700',
                'finishing' => 'bg-orange-100 text-orange-700 border-orange-300 dark:bg-orange-900/30 dark:text-orange-400 dark:border-orange-700',
                'ready' => 'bg-green-100 text-green-700 border-green-300 dark:bg-green-900/30 dark:text-green-400 dark:border-green-700',
                'delivered' => 'bg-gray-100 text-gray-600 border-gray-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600',
            ];
            $statusLabels = [
                'pending' => 'Menunggu', 'cutting' => 'Potong', 'sewing' => 'Jahit',
                'finishing' => 'Finishing', 'ready' => 'Siap', 'delivered' => 'Terkirim',
            ];
            $nextStatus = [
                'pending' => 'cutting', 'cutting' => 'sewing', 'sewing' => 'finishing',
                'finishing' => 'ready', 'ready' => 'delivered',
            ];
            $counts = $orders->groupBy('status')->map(fn($g) => $g->count());
        @endphp

        <div class="grid grid-cols-3 md:grid-cols-6 gap-2 mb-2">
            @foreach($statusLabels as $key => $label)
            <div class="bg-white dark:bg-gray-900 p-3 border-2 border-black dark:border-gray-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] text-center">
                <div class="text-xl font-black text-gray-900 dark:text-white">{{ $counts->get($key, 0) }}</div>
                <div class="text-[9px] font-bold text-gray-500 uppercase">{{ $label }}</div>
            </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-4">Semua Pesanan</h2>
            <div class="space-y-3">
                @forelse($orders as $order)
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-black text-gray-900 dark:text-white">{{ $order->po_number }}</span>
                                <span class="text-[9px] font-bold px-2 py-0.5 border {{ $statusColors[$order->status] ?? '' }}">{{ $statusLabels[$order->status] ?? $order->status }}</span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Pelanggan: <span class="font-bold text-gray-700 dark:text-gray-300">{{ $order->customer?->name ?? '-' }}</span></div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-black text-gray-900 dark:text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            @if($order->down_payment > 0)
                            <div class="text-[10px] text-green-600 dark:text-green-400">DP: Rp {{ number_format($order->down_payment, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-xs mb-2">
                        <div class="bg-white dark:bg-gray-900 p-2 border border-gray-200 dark:border-gray-700">
                            <div class="text-[10px] text-gray-400">Produk</div>
                            <div class="font-bold text-gray-900 dark:text-white">{{ $order->product?->name ?? '-' }}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-900 p-2 border border-gray-200 dark:border-gray-700">
                            <div class="text-[10px] text-gray-400">Warna Custom</div>
                            <div class="font-bold text-gray-900 dark:text-white">{{ $order->custom_color ?? '-' }}</div>
                        </div>
                        <div class="bg-white dark:bg-gray-900 p-2 border border-gray-200 dark:border-gray-700">
                            <div class="text-[10px] text-gray-400">Deadline</div>
                            <div class="font-bold {{ $order->deadline_date && \Carbon\Carbon::parse($order->deadline_date)->isPast() ? 'text-red-500' : 'text-gray-900 dark:text-white' }}">{{ $order->deadline_date ? \Carbon\Carbon::parse($order->deadline_date)->translatedFormat('d M Y') : '-' }}</div>
                        </div>
                    </div>
                    @if($order->notes)
                    <div class="text-[10px] text-gray-400 mb-2 italic">Catatan: {{ $order->notes }}</div>
                    @endif
                    <div class="flex gap-2 pt-1 border-t border-gray-200 dark:border-gray-700">
                        @if(isset($nextStatus[$order->status]))
                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="{{ $nextStatus[$order->status] }}">
                            <button type="submit" class="text-[9px] font-black text-green-600 dark:text-green-400 border border-green-400 px-2 py-1 hover:bg-green-50 dark:hover:bg-green-900/20">
                                LANJUT KE: {{ strtoupper($statusLabels[$nextStatus[$order->status]]) }}
                            </button>
                        </form>
                        @endif
                        <button @click="editOrder({{ $order->id }}, {{ $order->customer_id }}, {{ $order->product_id }}, @js($order->custom_color ?? ''), @js($order->order_date), @js($order->deadline_date ?? ''), {{ $order->total_price }}, {{ $order->down_payment }}, @js($order->notes ?? ''))" class="text-[9px] font-black text-yellow-600 border border-yellow-400 px-2 py-1 hover:bg-yellow-50">EDIT</button>
                        <button @click="if(confirm('Hapus pesanan {{ $order->po_number }}?')) { document.getElementById('delete-order-{{ $order->id }}').submit(); }" class="text-[9px] font-black text-red-600 border border-red-400 px-2 py-1 hover:bg-red-50">HAPUS</button>
                        <form id="delete-order-{{ $order->id }}" action="{{ route('orders.destroy', $order) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-sm text-gray-500">Belum ada pesanan.</div>
                @endforelse
            </div>
        </div>

        <!-- MODAL: Tambah Pesanan -->
        <div x-show="showAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showAdd = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[520px] max-w-[95vw] max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-black mb-4 text-gray-900 dark:text-white uppercase">PESANAN BARU</h3>
                <form action="{{ route('orders.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Pelanggan *</label>
                            <select name="customer_id" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                                <option value="">Pilih Pelanggan</option>
                                @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Produk *</label>
                            <select name="product_id" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                                <option value="">Pilih Produk</option>
                                @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Warna Custom</label>
                        <input type="text" name="custom_color" placeholder="Contoh: Dark Brown, Black Doff" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Tanggal Pesan *</label>
                            <input type="date" name="order_date" value="{{ date('Y-m-d') }}" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Deadline</label>
                            <input type="date" name="deadline_date" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Total Harga (Rp) *</label>
                            <input type="number" name="total_price" required min="0" placeholder="1500000" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">DP (Rp)</label>
                            <input type="number" name="down_payment" min="0" placeholder="500000" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Catatan</label>
                        <textarea name="notes" rows="2" placeholder="Catatan pesanan..." class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none"></textarea>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-green-500 text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">BUAT PESANAN</button>
                        <button type="button" @click="showAdd = false" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">BATAL</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Edit Pesanan -->
        <div x-show="showEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showEdit = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[520px] max-w-[95vw] max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-black mb-4 text-gray-900 dark:text-white uppercase">EDIT PESANAN</h3>
                <form :action="'/orders/' + editId" method="POST" class="space-y-3">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Pelanggan *</label>
                            <select name="customer_id" x-model="editCustomerId" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                                @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Produk *</label>
                            <select name="product_id" x-model="editProductId" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                                @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Warna Custom</label>
                        <input type="text" name="custom_color" x-model="editColor" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Tanggal Pesan *</label>
                            <input type="date" name="order_date" x-model="editOrderDate" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Deadline</label>
                            <input type="date" name="deadline_date" x-model="editDeadline" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Total Harga (Rp) *</label>
                            <input type="number" name="total_price" x-model="editPrice" required min="0" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">DP (Rp)</label>
                            <input type="number" name="down_payment" x-model="editDP" min="0" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Catatan</label>
                        <textarea name="notes" x-model="editNotes" rows="2" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none"></textarea>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-yellow-400 text-black border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">UPDATE</button>
                        <button type="button" @click="showEdit = false" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">BATAL</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function orderManager() {
            return {
                showAdd: false, showEdit: false,
                editId: '', editCustomerId: '', editProductId: '', editColor: '',
                editOrderDate: '', editDeadline: '', editPrice: 0, editDP: 0, editNotes: '',
                editOrder(id, cid, pid, color, orderDate, deadline, price, dp, notes) {
                    this.editId = id; this.editCustomerId = cid; this.editProductId = pid;
                    this.editColor = color; this.editOrderDate = orderDate; this.editDeadline = deadline;
                    this.editPrice = price; this.editDP = dp; this.editNotes = notes;
                    this.showEdit = true;
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
