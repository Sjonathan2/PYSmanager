<x-layouts.app title="Pemasok">
    <div x-data="supplierManager()" class="space-y-4">
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pemasok</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftar supplier bahan dan aksesoris jaket kulit.</p>
            </div>
            <button @click="showAdd = true" class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-2 text-sm font-black border-2 border-black shadow-[3px_3px_0px_rgba(0,0,0,1)] dark:shadow-[3px_3px_0px_rgba(107,114,128,1)] active:translate-x-[3px] active:translate-y-[3px] active:shadow-none transition-all">
                + Tambah Pemasok
            </button>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 border-2 border-green-600 text-green-800 dark:text-green-300 text-sm font-bold" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('success') }}
        </div>
        @endif

        @php
            $typeCounts = $suppliers->groupBy('supply_type')->map(fn($g) => $g->count());
        @endphp
        <div class="grid grid-cols-3 gap-3 mb-2">
            <div class="bg-white dark:bg-gray-900 p-3 border-2 border-black dark:border-gray-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] text-center">
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ $suppliers->count() }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase">Total</div>
            </div>
            @foreach(['Bahan Kulit', 'Resleting', 'Furing'] as $type)
            <div class="bg-white dark:bg-gray-900 p-3 border-2 border-black dark:border-gray-700 shadow-[2px_2px_0px_rgba(0,0,0,1)] text-center">
                <div class="text-2xl font-black text-orange-500">{{ $typeCounts->get($type, 0) }}</div>
                <div class="text-[10px] font-bold text-gray-500 uppercase">{{ $type }}</div>
            </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-4">Daftar Pemasok</h2>
            <div class="space-y-3">
                @forelse($suppliers as $s)
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <div class="font-bold text-sm text-gray-900 dark:text-white">{{ $s->name }}</div>
                            @if($s->contact_person)
                            <div class="text-xs text-gray-500 dark:text-gray-400">Kontak: {{ $s->contact_person }}</div>
                            @endif
                            @if($s->phone)
                            <div class="text-xs text-gray-500 dark:text-gray-400">Telp: {{ $s->phone }}</div>
                            @endif
                            @if($s->address)
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $s->address }}</div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @if($s->supply_type)
                            <span class="text-[10px] font-bold px-2 py-1 bg-orange-100 text-orange-700 border border-orange-300 dark:bg-orange-900/30 dark:text-orange-400 dark:border-orange-700">{{ $s->supply_type }}</span>
                            @endif
                            <button @click="editSupplier({{ $s->id }}, @js($s->name), @js($s->contact_person ?? ''), @js($s->phone ?? ''), @js($s->address ?? ''), @js($s->supply_type ?? ''))" class="text-[10px] font-black text-yellow-600 border border-yellow-400 px-2 py-1 hover:bg-yellow-50">EDIT</button>
                            <button @click="if(confirm('Hapus pemasok {{ addslashes($s->name) }}?')) { document.getElementById('delete-supplier-{{ $s->id }}').submit(); }" class="text-[10px] font-black text-red-600 border border-red-400 px-2 py-1 hover:bg-red-50">HAPUS</button>
                            <form id="delete-supplier-{{ $s->id }}" action="{{ route('suppliers.destroy', $s) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-sm text-gray-500">Belum ada pemasok.</div>
                @endforelse
            </div>
        </div>

        <!-- MODAL: Tambah Pemasok -->
        <div x-show="showAdd" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showAdd = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[420px] max-w-[90vw]">
                <h3 class="text-lg font-black mb-4 text-gray-900 dark:text-white uppercase">TAMBAH PEMASOK</h3>
                <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Nama Pemasok *</label>
                        <input type="text" name="name" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Kontak Person</label>
                            <input type="text" name="contact_person" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">No. HP</label>
                            <input type="text" name="phone" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Alamat</label>
                        <textarea name="address" rows="2" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Jenis Pasokan</label>
                        <select name="supply_type" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            <option value="">Pilih Jenis</option>
                            <option value="Bahan Kulit">Bahan Kulit</option>
                            <option value="Resleting">Resleting</option>
                            <option value="Furing">Furing</option>
                            <option value="Benang">Benang</option>
                            <option value="Aksesoris">Aksesoris</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-green-500 text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">SIMPAN</button>
                        <button type="button" @click="showAdd = false" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">BATAL</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Edit Pemasok -->
        <div x-show="showEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showEdit = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[420px] max-w-[90vw]">
                <h3 class="text-lg font-black mb-4 text-gray-900 dark:text-white uppercase">EDIT PEMASOK</h3>
                <form :action="'/suppliers/' + editId" method="POST" class="space-y-3">
                    @csrf @method('PUT')
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Nama Pemasok *</label>
                        <input type="text" name="name" x-model="editName" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Kontak Person</label>
                            <input type="text" name="contact_person" x-model="editContact" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
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
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Jenis Pasokan</label>
                        <select name="supply_type" x-model="editType" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            <option value="">Pilih Jenis</option>
                            <option value="Bahan Kulit">Bahan Kulit</option>
                            <option value="Resleting">Resleting</option>
                            <option value="Furing">Furing</option>
                            <option value="Benang">Benang</option>
                            <option value="Aksesoris">Aksesoris</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
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
        function supplierManager() {
            return {
                showAdd: false, showEdit: false,
                editId: '', editName: '', editContact: '', editPhone: '', editAddress: '', editType: '',
                editSupplier(id, name, contact, phone, address, type) {
                    this.editId = id; this.editName = name; this.editContact = contact;
                    this.editPhone = phone; this.editAddress = address; this.editType = type;
                    this.showEdit = true;
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
