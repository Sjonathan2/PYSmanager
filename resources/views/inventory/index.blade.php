<x-layouts.app title="Inventori">
    <div x-data="inventoryManager()" class="space-y-4">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Inventori Stok</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola model jaket dan stok varian.</p>
            </div>
            <button @click="showAddProduct = true" class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-2 text-sm font-black border-2 border-black shadow-[3px_3px_0px_rgba(0,0,0,1)] dark:shadow-[3px_3px_0px_rgba(107,114,128,1)] active:translate-x-[3px] active:translate-y-[3px] active:shadow-none transition-all">
                + Tambah Produk
            </button>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 border-2 border-green-600 text-green-800 dark:text-green-300 text-sm font-bold" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('success') }}
        </div>
        @endif

        <!-- Product List -->
        @forelse($products as $product)
        <div class="bg-white dark:bg-gray-900 p-5 border-2 border-black dark:border-gray-700 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <div class="font-black text-sm text-gray-900 dark:text-white uppercase">{{ $product->name }}</div>
                    <div class="text-[10px] text-gray-500 dark:text-gray-400">{{ $product->category ?? 'Tanpa Kategori' }} &bull; {{ $product->variants_count }} Varian &bull; Total Stok: {{ $product->variants->sum('stock') }}</div>
                </div>
                <div class="flex gap-2">
                    <button @click="editProduct({{ $product->id }}, @js($product->name), @js($product->category ?? ''), @js($product->description ?? ''))" class="text-[10px] font-black text-yellow-600 dark:text-yellow-400 border border-yellow-400 px-2 py-1 hover:bg-yellow-50 dark:hover:bg-yellow-900/20">EDIT</button>
                    <button @click="if(confirm('Hapus produk {{ addslashes($product->name) }} beserta semua variannya?')) { document.getElementById('delete-product-{{ $product->id }}').submit(); }" class="text-[10px] font-black text-red-600 dark:text-red-400 border border-red-400 px-2 py-1 hover:bg-red-50 dark:hover:bg-red-900/20">HAPUS</button>
                    <form id="delete-product-{{ $product->id }}" action="{{ route('products.destroy', $product) }}" method="POST" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                    <button @click="showAddVariant = true; selectedProductId = {{ $product->id }}; selectedProductName = @js($product->name)" class="text-[10px] font-black text-blue-600 dark:text-blue-400 border border-blue-400 px-2 py-1 hover:bg-blue-50 dark:hover:bg-blue-900/20">+ VARIAN</button>
                </div>
            </div>

            @if($product->variants->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b-2 border-gray-200 dark:border-gray-700">
                            <th class="text-left py-1 font-black text-[9px] uppercase text-gray-400">SKU</th>
                            <th class="text-left py-1 font-black text-[9px] uppercase text-gray-400">Size</th>
                            <th class="text-left py-1 font-black text-[9px] uppercase text-gray-400">Warna</th>
                            <th class="text-right py-1 font-black text-[9px] uppercase text-gray-400">Stok</th>
                            <th class="text-right py-1 font-black text-[9px] uppercase text-gray-400">Harga</th>
                            <th class="text-right py-1 font-black text-[9px] uppercase text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $v)
                        <tr class="border-b border-gray-100 dark:border-gray-800 {{ $v->stock <= 5 ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                            <td class="py-1.5 font-mono text-[10px] text-gray-600 dark:text-gray-300">{{ $v->sku }}</td>
                            <td class="py-1.5 font-bold text-gray-900 dark:text-white">{{ $v->size }}</td>
                            <td class="py-1.5 text-gray-600 dark:text-gray-300">{{ $v->color }}</td>
                            <td class="py-1.5 text-right font-black {{ $v->stock <= 5 ? 'text-red-500' : 'text-gray-900 dark:text-white' }}">{{ $v->stock }}</td>
                            <td class="py-1.5 text-right text-gray-600 dark:text-gray-300">Rp {{ number_format($v->price, 0, ',', '.') }}</td>
                            <td class="py-1.5 text-right">
                                <button @click="editVariant({{ $v->id }}, @js($v->sku), @js($v->size), @js($v->color), {{ $v->stock }}, {{ $v->price }})" class="text-[9px] font-black text-yellow-600 hover:underline mr-2">Edit</button>
                                <button @click="if(confirm('Hapus varian {{ $v->sku }}?')) { document.getElementById('delete-variant-{{ $v->id }}').submit(); }" class="text-[9px] font-black text-red-600 hover:underline">Hapus</button>
                                <form id="delete-variant-{{ $v->id }}" action="{{ route('variants.destroy', $v) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-[10px] text-gray-400 italic">Belum ada varian. Klik "+ VARIAN" untuk menambahkan.</div>
            @endif
        </div>
        @empty
        <div class="bg-white dark:bg-gray-900 p-10 border-2 border-black dark:border-gray-700 text-center text-sm text-gray-500 shadow-[4px_4px_0px_rgba(0,0,0,1)]">
            Belum ada data produk. Klik "+ Tambah Produk" untuk mulai.
        </div>
        @endforelse

        <!-- MODAL: Tambah Produk -->
        <div x-show="showAddProduct" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showAddProduct = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[420px] max-w-[90vw]">
                <h3 class="text-lg font-black mb-4 text-gray-900 dark:text-white uppercase">TAMBAH PRODUK</h3>
                <form action="{{ route('products.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Nama Produk *</label>
                        <input type="text" name="name" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Kategori</label>
                        <select name="category" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            <option value="">Pilih Kategori</option>
                            <option value="Biker Jacket">Biker Jacket</option>
                            <option value="Bomber">Bomber</option>
                            <option value="Blazer">Blazer</option>
                            <option value="Vest">Vest</option>
                            <option value="Custom">Custom</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Deskripsi</label>
                        <textarea name="description" rows="2" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-green-500 text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">SIMPAN</button>
                        <button type="button" @click="showAddProduct = false" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">BATAL</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Edit Produk -->
        <div x-show="showEditProduct" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showEditProduct = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[420px] max-w-[90vw]">
                <h3 class="text-lg font-black mb-4 text-gray-900 dark:text-white uppercase">EDIT PRODUK</h3>
                <form :action="'/products/' + editProductId" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Nama Produk *</label>
                        <input type="text" name="name" x-model="editProductName" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Kategori</label>
                        <select name="category" x-model="editProductCategory" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                            <option value="">Pilih Kategori</option>
                            <option value="Biker Jacket">Biker Jacket</option>
                            <option value="Bomber">Bomber</option>
                            <option value="Blazer">Blazer</option>
                            <option value="Vest">Vest</option>
                            <option value="Custom">Custom</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Deskripsi</label>
                        <textarea name="description" x-model="editProductDesc" rows="2" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-yellow-400 text-black border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">UPDATE</button>
                        <button type="button" @click="showEditProduct = false" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">BATAL</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Tambah Varian -->
        <div x-show="showAddVariant" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showAddVariant = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[480px] max-w-[90vw]">
                <h3 class="text-lg font-black mb-1 text-gray-900 dark:text-white uppercase">TAMBAH VARIAN</h3>
                <p class="text-xs text-gray-500 mb-4">Untuk: <span class="font-bold" x-text="selectedProductName"></span></p>
                <form action="{{ route('variants.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="product_id" :value="selectedProductId">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">SKU *</label>
                            <input type="text" name="sku" required placeholder="SKU-XXXX-0000" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-mono">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Size *</label>
                            <select name="size" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                                <option value="Custom">Custom</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Warna *</label>
                            <input type="text" name="color" required placeholder="Black, Brown, Tan..." class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Stok *</label>
                            <input type="number" name="stock" required min="0" value="0" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Harga Jual (Rp) *</label>
                        <input type="number" name="price" required min="0" placeholder="500000" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-bold">
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-blue-500 text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">SIMPAN VARIAN</button>
                        <button type="button" @click="showAddVariant = false" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">BATAL</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: Edit Varian -->
        <div x-show="showEditVariant" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showEditVariant = false" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[480px] max-w-[90vw]">
                <h3 class="text-lg font-black mb-4 text-gray-900 dark:text-white uppercase">EDIT VARIAN</h3>
                <form :action="'/variants/' + editVariantId" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">SKU *</label>
                            <input type="text" name="sku" x-model="editVariantSku" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-mono">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Size *</label>
                            <select name="size" x-model="editVariantSize" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                                <option value="Custom">Custom</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Warna *</label>
                            <input type="text" name="color" x-model="editVariantColor" required class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Stok *</label>
                            <input type="number" name="stock" x-model="editVariantStock" required min="0" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Harga Jual (Rp) *</label>
                        <input type="number" name="price" x-model="editVariantPrice" required min="0" class="w-full border-2 border-black dark:border-gray-600 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none font-bold">
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-yellow-400 text-black border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">UPDATE</button>
                        <button type="button" @click="showEditVariant = false" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white border-2 border-black font-black py-2 text-sm hover:brightness-110 active:translate-y-1">BATAL</button>
                    </div>
                </form>
            </div>
        </div>

        @push('scripts')
        <script>
            function inventoryManager() {
                return {
                    showAddProduct: false,
                    showEditProduct: false,
                    showAddVariant: false,
                    showEditVariant: false,
                    selectedProductId: null,
                    selectedProductName: '',
                    editProductId: '',
                    editProductName: '',
                    editProductCategory: '',
                    editProductDesc: '',
                    editVariantId: '',
                    editVariantSku: '',
                    editVariantSize: '',
                    editVariantColor: '',
                    editVariantStock: 0,
                    editVariantPrice: 0,
                    editProduct(id, name, category, desc) {
                        this.editProductId = id;
                        this.editProductName = name;
                        this.editProductCategory = category;
                        this.editProductDesc = desc;
                        this.showEditProduct = true;
                    },
                    editVariant(id, sku, size, color, stock, price) {
                        this.editVariantId = id;
                        this.editVariantSku = sku;
                        this.editVariantSize = size;
                        this.editVariantColor = color;
                        this.editVariantStock = stock;
                        this.editVariantPrice = price;
                        this.showEditVariant = true;
                    }
                }
            }
        </script>
        @endpush
    </div>
</x-layouts.app>
