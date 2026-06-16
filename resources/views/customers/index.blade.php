<x-layouts.app title="Pelanggan">
    <div x-data="customerManager()" class="space-y-4">
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pelanggan</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftar pelanggan dan ukuran tubuh custom jaket.</p>
            </div>
            <button @click="showAdd = true" class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-2 text-sm font-black border-2 border-black shadow-[3px_3px_0px_rgba(0,0,0,1)] dark:shadow-[3px_3px_0px_rgba(107,114,128,1)] active:translate-x-[3px] active:translate-y-[3px] active:shadow-none transition-all">
                + Tambah Pelanggan
            </button>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 border-2 border-green-600 text-green-800 dark:text-green-300 text-sm font-bold" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('success') }}
        </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-3 mb-2">
            <div class="bg-white dark:bg-gray-900 p-3 border-2 border-black dark:border-gray-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] text-center">
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ $customers->count() }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase">Total</div>
            </div>
            <div class="bg-white dark:bg-gray-900 p-3 border-2 border-black dark:border-gray-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] text-center">
                <div class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ $customers->where('notes', '!=', null)->count() }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase">Ada Catatan</div>
            </div>
            <div class="bg-white dark:bg-gray-900 p-3 border-2 border-black dark:border-gray-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] text-center">
                <div class="text-2xl font-black text-green-600 dark:text-green-400">{{ $customers->where('phone', '!=', null)->count() }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase">Ada No. HP</div>
            </div>
        </div>

        <!-- Customer List -->
        <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-4">Daftar Pelanggan</h2>
            <div class="space-y-3">
                @forelse($customers as $c)
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <div class="font-bold text-sm text-gray-900 dark:text-white">{{ $c->name }}</div>
                            @if($c->phone)
                            <div class="text-xs text-gray-500 dark:text-gray-400">Telp: {{ $c->phone }}</div>
                            @endif
                            @if($c->address)
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $c->address }}</div>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button @click="editCustomer({{ $c->id }}, @js($c->name), @js($c->phone ?? ''), @js($c->address ?? ''), {{ $c->chest_width ?? 'null' }}, {{ $c->shoulder_width ?? 'null' }}, {{ $c->arm_length ?? 'null' }}, {{ $c->body_length ?? 'null' }}, {{ $c->belly_circumference ?? 'null' }}, @js($c->notes ?? ''))" class="text-[10px] font-black text-yellow-600 dark:text-yellow-400 border border-yellow-400 px-2 py-1 hover:bg-yellow-50">EDIT</button>
                            <button @click="if(confirm('Hapus pelanggan {{ addslashes($c->name) }}?')) { document.getElementById('delete-customer-{{ $c->id }}').submit(); }" class="text-[10px] font-black text-red-600 dark:text-red-400 border border-red-400 px-2 py-1 hover:bg-red-50">HAPUS</button>
                            <form id="delete-customer-{{ $c->id }}" action="{{ route('customers.destroy', $c) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                        </div>
                    </div>
                    @php
                        $measurements = collect([
                            ['Lebar Dada', $c->chest_width],
                            ['Lebar Bahu', $c->shoulder_width],
                            ['Panjang Lengan', $c->arm_length],
                            ['Panjang Badan', $c->body_length],
                            ['Lingkar Perut', $c->belly_circumference],
                        ]);
                        $hasM = $measurements->some(fn($m) => $m[1] !== null);
                    @endphp
                    @if($hasM)
                    <div class="grid grid-cols-5 gap-2 mt-2">
                        @foreach($measurements as $m)
                        <div class="text-center p-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                            <div class="text-[10px] text-gray-400 uppercase">{{ $m[0] }}</div>
                            <div class="text-xs font-bold text-gray-900 dark:text-white">{{ $m[1] !== null ? $m[1] . ' cm' : '-' }}</div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @if($c->notes)
                    <div class="text-[10px] text-gray-400 mt-2 italic">Catatan: {{ $c->notes }}</div>
                    @endif
                </div>
                @empty
                <div class="text-center py-10 text-sm text-gray-500">Belum ada pelanggan.</div>
                @endforelse
            </div>
        </div>

        <!-- MODAL: Tambah Pelanggan -->
        <div x-show="showAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showAdd = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[520px] max-w-[95vw] max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-black mb-4 text-gray-900 dark:text-white uppercase">TAMBAH PELANGGAN</h3>
                <form action="{{ route('customers.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Nama Lengkap *</label>
                            <input type="text" name="name" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">No. HP</label>
                            <input type="text" name="phone" placeholder="0812xxxx" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Alamat</label>
                        <textarea name="address" rows="2" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none"></textarea>
                    </div>
                    <div class="border-t-2 border-gray-200 dark:border-gray-700 pt-3">
                        <div class="text-[10px] font-bold text-gray-400 uppercase mb-2">Ukuran Tubuh (cm) - Untuk Custom Jaket</div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="text-[10px] text-gray-500">Lebar Dada</label>
                                <input type="number" name="chest_width" step="0.1" min="0" placeholder="cm" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500">Lebar Bahu</label>
                                <input type="number" name="shoulder_width" step="0.1" min="0" placeholder="cm" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500">Panjang Lengan</label>
                                <input type="number" name="arm_length" step="0.1" min="0" placeholder="cm" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500">Panjang Badan</label>
                                <input type="number" name="body_length" step="0.1" min="0" placeholder="cm" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500">Lingkar Perut</label>
                                <input type="number" name="belly_circumference" step="0.1" min="0" placeholder="cm" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Catatan</label>
                        <textarea name="notes" rows="2" placeholder="Catatan tambahan..." class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none"></textarea>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-green-500 text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">SIMPAN</button>
                        <button type="button" @click="showAdd = false" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">BATAL</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Edit Pelanggan -->
        <div x-show="showEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showEdit = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[520px] max-w-[95vw] max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-black mb-4 text-gray-900 dark:text-white uppercase">EDIT PELANGGAN</h3>
                <form :action="'/customers/' + editId" method="POST" class="space-y-3">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Nama Lengkap *</label>
                            <input type="text" name="name" x-model="editName" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">No. HP</label>
                            <input type="text" name="phone" x-model="editPhone" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Alamat</label>
                        <textarea name="address" x-model="editAddress" rows="2" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none"></textarea>
                    </div>
                    <div class="border-t-2 border-gray-200 dark:border-gray-700 pt-3">
                        <div class="text-[10px] font-bold text-gray-400 uppercase mb-2">Ukuran Tubuh (cm)</div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="text-[10px] text-gray-500">Lebar Dada</label>
                                <input type="number" name="chest_width" x-model="editChest" step="0.1" min="0" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500">Lebar Bahu</label>
                                <input type="number" name="shoulder_width" x-model="editShoulder" step="0.1" min="0" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500">Panjang Lengan</label>
                                <input type="number" name="arm_length" x-model="editArm" step="0.1" min="0" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500">Panjang Badan</label>
                                <input type="number" name="body_length" x-model="editBody" step="0.1" min="0" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500">Lingkar Perut</label>
                                <input type="number" name="belly_circumference" x-model="editBelly" step="0.1" min="0" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            </div>
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
        function customerManager() {
            return {
                showAdd: false, showEdit: false,
                editId: '', editName: '', editPhone: '', editAddress: '',
                editChest: null, editShoulder: null, editArm: null, editBody: null, editBelly: null, editNotes: '',
                editCustomer(id, name, phone, address, chest, shoulder, arm, body, belly, notes) {
                    this.editId = id; this.editName = name; this.editPhone = phone; this.editAddress = address;
                    this.editChest = chest; this.editShoulder = shoulder; this.editArm = arm;
                    this.editBody = body; this.editBelly = belly; this.editNotes = notes;
                    this.showEdit = true;
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
