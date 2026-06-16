<x-layouts.app title="Detail Container">
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail {{ $zone->name ?? 'Container' }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Atur susunan vertical box dan alokasikan stok.</p>
        </div>
        <a href="{{ route('visual-inventory.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-bold border-2 border-blue-600 dark:border-blue-400 px-3 py-1 rounded">&larr; KEMBALI KE MAP</a>
    </div>

    <div x-data="containerDetail()" class="flex flex-col md:flex-row gap-6 h-[75vh] min-h-[600px] select-none">
        
        <!-- SIDEBAR: DETAILS & EDIT -->
        <div class="w-full md:w-72 flex flex-col gap-4 h-full">
            
            <!-- DETAILS BLOCK -->
            <div class="bg-white dark:bg-gray-800 border-2 border-black dark:border-gray-300 flex flex-col flex-1 min-h-[250px] shadow-sm">
                <div class="border-b-2 border-black dark:border-gray-300 text-center py-2">
                    <h2 class="font-black text-xl tracking-wider text-black dark:text-white uppercase">Details</h2>
                </div>
                <div class="p-3 overflow-y-auto flex-1 text-sm font-medium text-black dark:text-gray-200 space-y-1">
                    <template x-for="box in boxes" :key="box.id">
                        <div class="flex items-center gap-2">
                            <span x-text="(box.name || 'Box ?') + ' = '"></span>
                            <span x-text="box.placements.length + ' Item'" class="flex-1 truncate text-gray-500 dark:text-gray-400"></span>
                            <span class="inline-block w-4 h-4 border border-black dark:border-gray-500 rounded-sm shrink-0" :style="'background-color: ' + (box.color || '#EF4444')"></span>
                        </div>
                    </template>
                    <div x-show="boxes.length === 0" class="text-gray-400 italic">Belum ada box.</div>
                </div>
            </div>

            <!-- EDIT TOOLS BLOCK -->
            <div class="bg-white dark:bg-gray-800 border-2 border-black dark:border-gray-300 flex flex-col h-auto shadow-sm">
                <div class="border-b-2 border-black dark:border-gray-300 text-center py-2">
                    <h2 class="font-black text-xl tracking-wider text-black dark:text-white uppercase">Edit</h2>
                </div>
                <div class="p-4 grid grid-cols-2 gap-3 bg-gray-100 dark:bg-gray-700/50">
                    <!-- Palet Warna -->
                    <button @click="toggleTool('color')" :class="activeTool === 'color' ? 'ring-4 ring-blue-500 bg-gray-300 dark:bg-gray-600' : 'bg-gray-500 dark:bg-gray-500 hover:brightness-110'" class="aspect-square border-2 border-black dark:border-gray-300 rounded flex items-center justify-center transition-all" title="Ubah Warna">
                        <div class="w-8 h-8 bg-green-500 border border-white"></div>
                    </button>
                    <!-- Kotak Dashed (Add Box/Select Container) -->
                    <button @click="toggleTool('add_box')" :class="activeTool === 'add_box' ? 'ring-4 ring-blue-500 bg-gray-300 dark:bg-gray-600' : 'bg-gray-500 dark:bg-gray-500 hover:brightness-110'" class="aspect-square border-2 border-black dark:border-gray-300 rounded flex items-center justify-center transition-all" title="Tambah Box Baru">
                        <div class="w-8 h-8 border-2 border-dashed border-white flex items-center justify-center text-white font-bold text-xl leading-none">+</div>
                    </button>
                    <!-- Text (T) -->
                    <button @click="toggleTool('text')" :class="activeTool === 'text' ? 'ring-4 ring-blue-500 bg-gray-300 dark:bg-gray-600' : 'bg-gray-500 dark:bg-gray-500 hover:brightness-110'" class="aspect-square border-2 border-black dark:border-gray-300 rounded flex items-center justify-center transition-all" title="Beri Nama">
                        <span class="text-4xl font-serif font-bold text-white leading-none">T</span>
                    </button>
                    <!-- Trash -->
                    <button @click="toggleTool('trash')" :class="activeTool === 'trash' ? 'ring-4 ring-red-500 bg-gray-300 dark:bg-gray-600' : 'bg-gray-500 dark:bg-gray-500 hover:brightness-110'" class="aspect-square border-2 border-black dark:border-gray-300 rounded flex items-center justify-center transition-all" title="Hapus Box">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
                <div class="px-3 py-2 text-xs font-bold text-center bg-gray-200 dark:bg-gray-900 border-t-2 border-black dark:border-gray-300 text-black dark:text-gray-300">
                    <span x-text="instructionText"></span>
                </div>
            </div>
        </div>

        <!-- VERTICAL BOXES VIEW (Tengah) -->
        <div class="w-40 bg-white dark:bg-gray-800 border-2 border-black dark:border-gray-300 shadow-sm flex flex-col items-center py-4 px-2 overflow-y-auto">
            
            <template x-for="(box, index) in boxes" :key="box.id">
                <!-- Membalikkan urutan visual jika diinginkan tumpukan dari bawah ke atas, tapi standar top to bottom juga oke -->
                <div 
                    @click="boxAction(box)"
                    class="w-full h-14 border-2 border-black dark:border-gray-400 mb-2 flex items-center justify-center text-sm font-bold cursor-pointer transition-all relative overflow-hidden"
                    :class="selectedBox && selectedBox.id === box.id ? 'border-blue-500 border-dashed border-4 dark:border-blue-400' : ''"
                    :style="'background-color: ' + (box.color || '#EF4444') + '; color: ' + getContrastYIQ(box.color || '#EF4444')"
                >
                    <span class="z-10 bg-black/20 px-2 py-1 rounded truncate w-full text-center" x-text="box.name || 'Box ' + (index+1)"></span>
                </div>
            </template>

            <div x-show="boxes.length === 0" class="text-sm text-gray-400 text-center mt-10">
                Gunakan Tool [+] untuk menambah wadah box vertical.
            </div>

        </div>

        <!-- BOX CONTENTS / STOCKS (Kanan) -->
        <div class="flex-1 bg-white dark:bg-gray-800 border-2 border-black dark:border-gray-300 shadow-sm flex flex-col overflow-hidden relative">
            
            <div x-show="!selectedBox" class="absolute inset-0 flex items-center justify-center text-gray-500 font-bold bg-gray-50/80 dark:bg-gray-900/80 z-10">
                &larr; Pilih salah satu box untuk melihat isinya
            </div>

            <!-- Area Judul Box yang aktif -->
            <div class="border-b-2 border-black dark:border-gray-300 py-3 px-4 flex justify-between items-center bg-gray-100 dark:bg-gray-900">
                <h2 class="font-black text-lg text-black dark:text-white uppercase" x-text="selectedBox ? (selectedBox.name || 'Box') : 'Box 1'"></h2>
                <button @click="showAddStockModal = true" class="bg-black dark:bg-gray-300 text-white dark:text-black px-4 py-1.5 font-bold border-2 border-black dark:border-gray-300 hover:bg-gray-800 dark:hover:bg-white text-sm">
                    + TARUH STOK SINI
                </button>
            </div>

            <!-- List Stok (Grid 2 Kolom seperti di gambar) -->
            <div class="p-4 overflow-y-auto flex-1 bg-gray-50 dark:bg-gray-800">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    
                    <template x-for="(placement, idx) in (selectedBox ? selectedBox.placements : [])" :key="placement.id">
                        <div class="border-2 border-black dark:border-gray-400 p-2 flex justify-between items-center bg-white dark:bg-gray-700">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <span class="border-2 border-black dark:border-gray-400 font-bold px-2 py-0.5 text-xs text-black dark:text-white" x-text="placement.product_variant.size"></span>
                                <span class="text-xs font-bold text-black dark:text-white truncate" x-text="placement.product_variant.product.name + ' - ' + placement.product_variant.color"></span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xs font-black text-blue-600 dark:text-blue-400" x-text="placement.quantity + 'x'"></span>
                                <button @click="removeStock(placement.id)" class="text-red-600 dark:text-red-400 hover:text-red-800 font-black px-1" title="Keluarkan Stok">X</button>
                            </div>
                        </div>
                    </template>
                    
                </div>
            </div>

        </div>

        <!-- Modal Pilihan Stok (Dipindah ke dalam x-data) -->
        <div x-show="showAddStockModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showAddStockModal = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[500px]">
                <h3 class="text-2xl font-black mb-4 border-b-4 border-black pb-2 text-black dark:text-white uppercase" style="font-family: Impact, sans-serif;">PILIH STOK INVENTORI</h3>
                
                <div class="mb-4 space-y-2 max-h-64 overflow-y-auto pr-2">
                    @foreach($productVariants as $variant)
                    <label class="flex items-center gap-3 p-2 border-2 border-black dark:border-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer bg-white dark:bg-gray-800 shadow-[2px_2px_0px_rgba(0,0,0,1)] transition-all">
                        <input type="radio" name="variant_id" x-model="selectedVariantId" value="{{ $variant->id }}" class="w-5 h-5 accent-black ml-2">
                        <div class="flex-1">
                            <div class="font-black text-sm text-black dark:text-white uppercase">{{ $variant->product->name }}</div>
                            <div class="text-xs font-bold text-gray-600 dark:text-gray-300">Size: {{ $variant->size }} | Color: {{ $variant->color }}</div>
                        </div>
                        <div class="text-xs font-black bg-blue-100 text-blue-800 px-2 py-1 border-2 border-blue-800">SISA: {{ $variant->stock }}</div>
                    </label>
                    @endforeach
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-black text-black dark:text-gray-200 mb-1">JUMLAH (QTY):</label>
                    <input type="number" x-model="stockQuantity" min="1" class="w-full border-4 border-black bg-white dark:bg-gray-900 p-2 font-black text-xl dark:text-white" value="1">
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button @click="showAddStockModal = false" class="px-6 py-2 font-black text-black dark:text-white border-4 border-black hover:bg-gray-100 dark:hover:bg-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] active:translate-x-1 active:translate-y-1 active:shadow-none">BATAL</button>
                    <button @click="placeStock()" class="bg-[#84CC16] text-black px-6 py-2 font-black border-4 border-black hover:brightness-110 shadow-[4px_4px_0px_rgba(0,0,0,1)] active:translate-x-1 active:translate-y-1 active:shadow-none">SIMPAN KE BOX</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Logika -->
    <script>
        function containerDetail() {
            return {
                zoneId: {{ $zone->id }},
                boxes: @json($zone->storageBoxes),
                selectedBox: null,
                activeTool: null,
                instructionText: 'Pilih tool Edit.',
                
                showAddStockModal: false,
                selectedVariantId: null,
                stockQuantity: 1,

                toggleTool(tool) {
                    if (tool === 'add_box') {
                        this.addBox();
                        return;
                    }

                    if (this.activeTool === tool) {
                        this.activeTool = null;
                        this.instructionText = 'Pilih tool Edit.';
                    } else {
                        this.activeTool = tool;
                        const texts = {
                            'color': 'Klik box untuk mengubah warnanya secara acak.',
                            'text': 'Klik box untuk mengubah namanya.',
                            'trash': 'Klik box untuk menghapus.'
                        };
                        this.instructionText = texts[tool] || '';
                    }
                },

                boxAction(box) {
                    if (this.activeTool === 'trash') {
                        if (box.placements && box.placements.length > 0) {
                            alert('TIDAK BISA DIHAPUS! Ada stok di dalam box ini.');
                            return;
                        }
                        if (confirm('Hapus box ini?')) {
                            // Dummy remove
                            this.boxes = this.boxes.filter(b => b.id !== box.id);
                            if(this.selectedBox && this.selectedBox.id === box.id) this.selectedBox = null;
                        }
                    } else if (this.activeTool === 'text') {
                        let newName = prompt('Nama Box:', box.name || '');
                        if (newName !== null) box.name = newName;
                    } else if (this.activeTool === 'color') {
                        const colors = ['#EF4444', '#10B981', '#3B82F6', '#F59E0B', '#A855F7', '#06B6D4'];
                        box.color = colors[Math.floor(Math.random() * colors.length)];
                    } else {
                        // Default action: Select box to view stock
                        this.selectedBox = box;
                    }
                },

                addBox() {
                    let newName = prompt('Nama Box Baru (misal: Box Atas):');
                    if (!newName) return;
                    
                    let newBox = {
                        id: Date.now(),
                        name: newName,
                        color: '#EF4444',
                        placements: []
                    };
                    this.boxes.push(newBox);
                },

                placeStock() {
                    if (!this.selectedVariantId || !this.selectedBox) return;
                    if (this.stockQuantity < 1) return;

                    alert('Simulasi: Stok masuk ke ' + this.selectedBox.name + ' sebanyak ' + this.stockQuantity + ' pcs.\n(API Real akan memotong stok di inventory utama).');
                    this.showAddStockModal = false;
                    location.reload(); 
                },
                
                removeStock(placementId) {
                    if(confirm('Keluarkan stok ini dari box?')) {
                         alert('Simulasi: Stok berhasil dikeluarkan dan dikembalikan ke inventory utama.');
                         location.reload();
                    }
                },

                getContrastYIQ(hexcolor){
                    hexcolor = hexcolor.replace("#", "");
                    if(hexcolor.length === 3) hexcolor = hexcolor.split('').map(x => x+x).join('');
                    var r = parseInt(hexcolor.substr(0,2),16);
                    var g = parseInt(hexcolor.substr(2,2),16);
                    var b = parseInt(hexcolor.substr(4,2),16);
                    var yiq = ((r*299)+(g*587)+(b*114))/1000;
                    return (yiq >= 128) ? 'black' : 'white';
                }
            }
        }
    </script>
</x-layouts.app>