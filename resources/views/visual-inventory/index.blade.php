<x-layouts.app title="Visual Inventory">
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Denah Gudang (Visual Map)</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Atur tata letak objek fisik dan area penyimpanan jaket.</p>
    </div>

    <!-- UI Menggunakan Alpine.js -->
    <div x-data="visualMap()" x-init="initMap()" class="flex flex-row gap-6 h-[75vh] min-h-[600px] select-none items-start">
        
        <!-- SIDEBAR KIRI: DETAILS & EDIT -->
        <div class="w-64 flex flex-col gap-4 h-full shrink-0">
            
            <!-- DETAILS BLOCK -->
            <div class="bg-white dark:bg-gray-800 border-4 border-black flex flex-col flex-1 min-h-0">
                <div class="border-b-4 border-black text-center py-2 bg-white dark:bg-gray-800 shrink-0">
                    <h2 class="font-black text-2xl tracking-widest text-black dark:text-white uppercase" style="font-family: Impact, sans-serif;">DETAILS</h2>
                </div>
                <div class="p-4 overflow-y-auto flex-1 text-base font-bold text-black dark:text-gray-200 space-y-2">
                    <template x-for="(zone, index) in zones" :key="zone.id">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-4 h-4 border-2 border-black dark:border-gray-500 rounded-sm shrink-0" :style="'background-color: ' + zone.color"></span>
                            <span>=</span>
                            <span x-text="zone.name ? zone.name : '?'" class="flex-1 truncate"></span>
                        </div>
                    </template>
                    <div x-show="zones.length === 0" class="text-gray-400 italic">Belum ada area.</div>
                </div>
            </div>

            <!-- EDIT TOOLS BLOCK -->
            <div class="bg-white dark:bg-gray-800 border-4 border-black flex flex-col shrink-0">
                <div class="border-b-4 border-black text-center py-2 bg-white dark:bg-gray-800 shrink-0">
                    <h2 class="font-black text-2xl tracking-widest text-black dark:text-white uppercase" style="font-family: Impact, sans-serif;">EDIT</h2>
                </div>
                <!-- Grid untuk penempatan icon edit persis gambar -->
                <div class="p-6 grid grid-cols-3 gap-6 items-center justify-items-center">
                    
                    <!-- Baris 1 -->
                    <!-- Palet Warna -->
                    <button @click="toggleTool('color')" :class="activeTool === 'color' ? 'bg-[#555555]' : 'bg-[#777777]'" class="w-14 h-14 flex items-center justify-center border-none focus:outline-none transition-all shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-y-1 active:translate-x-1 active:shadow-none border-2 border-black">
                        <div class="w-8 h-8 bg-[#84CC16] border-[3px] border-white"></div>
                    </button>
                    <!-- Kotak Solid (Object) -->
                    <button @click="toggleTool('draw_object')" :class="activeTool === 'draw_object' ? 'bg-[#555555]' : 'bg-[#777777]'" class="w-14 h-14 flex items-center justify-center border-none focus:outline-none transition-all shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-y-1 active:translate-x-1 active:shadow-none border-2 border-black">
                        <div class="w-8 h-8 border-[3px] border-white bg-transparent"></div>
                    </button>
                    <!-- Text (T) -->
                    <button @click="toggleTool('text')" :class="activeTool === 'text' ? 'bg-[#555555]' : 'bg-[#777777]'" class="w-14 h-14 flex items-center justify-center border-none focus:outline-none transition-all shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-y-1 active:translate-x-1 active:shadow-none border-2 border-black">
                        <span class="text-3xl font-serif text-white leading-none">T</span>
                    </button>

                    <!-- Baris 2 -->
                    <!-- Move (Panah) -->
                    <button @click="toggleTool('move')" :class="activeTool === 'move' ? 'bg-[#555555]' : 'bg-[#777777]'" class="w-14 h-14 flex items-center justify-center border-none focus:outline-none transition-all shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-y-1 active:translate-x-1 active:shadow-none border-2 border-black">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9l-3 3m0 0l3 3m-3-3h8m-9 3l3 3m0 0l-3 3m3-3H6m14-3l-3-3m0 0l3-3m-3 3H9m3-9v8m0 0v8m0-8h8" /></svg>
                    </button>
                    <!-- Kotak Dashed (Container) -->
                    <button @click="toggleTool('draw_container')" :class="activeTool === 'draw_container' ? 'bg-[#555555]' : 'bg-[#777777]'" class="w-14 h-14 flex items-center justify-center border-none focus:outline-none transition-all shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-y-1 active:translate-x-1 active:shadow-none border-2 border-black">
                        <div class="w-8 h-8 border-[3px] border-dashed border-white bg-transparent"></div>
                    </button>
                    <!-- Trash -->
                    <button @click="toggleTool('trash')" :class="activeTool === 'trash' ? 'bg-[#555555]' : 'bg-[#777777]'" class="w-14 h-14 flex items-center justify-center border-none focus:outline-none col-span-1 transition-all shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-y-1 active:translate-x-1 active:shadow-none border-2 border-black">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Box Konfirmasi -->
            <div x-show="showConfirmation" style="display: none;" class="bg-white dark:bg-gray-800 border-4 border-black flex flex-col p-4 shrink-0 shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
                <span class="font-black text-black dark:text-white uppercase tracking-wider text-center mb-3">SIMPAN AREA INI?</span>
                <div class="flex gap-3">
                    <button @click="showColorPicker = true; showConfirmation = false;" class="flex-1 bg-[#84CC16] text-black border-2 border-black font-black py-2 hover:brightness-110 active:translate-y-1">YA</button>
                    <button @click="cancelDrawing()" class="flex-1 bg-[#EF4444] text-white border-2 border-black font-black py-2 hover:brightness-110 active:translate-y-1">BATAL</button>
                </div>
            </div>

        </div>

        <!-- MAIN GRID CANVAS (Area Kanan) -->
        <div class="flex-1 h-full bg-white dark:bg-gray-900 border-4 border-black flex items-start justify-start relative overflow-auto shadow-[4px_4px_0px_rgba(0,0,0,1)] dark:shadow-[4px_4px_0px_rgba(107,114,128,1)]">
            
            <div x-show="loading" class="absolute inset-0 bg-white/70 dark:bg-black/70 flex items-center justify-center z-50">
                <span class="font-black text-xl text-black dark:text-white animate-pulse">Memproses...</span>
            </div>

            <!-- Floating Confirmation Toolbar -->
            <!-- Dihapus dari sini karena sudah dipindah ke bawah EDIT BLOCK -->
                
            <!-- GRID -->
            <div 
                class="grid border-2 border-black bg-white dark:bg-gray-800 shrink-0 w-max h-max relative overflow-hidden" 
                :style="'grid-template-columns: repeat(' + gridCols + ', 40px);'"
                @mouseleave="endAction()"
                @mouseup.window="endAction()"
            >
                <template x-for="y in gridRows" :key="'row-'+y">
                    <template x-for="x in gridCols" :key="'cell-'+x+'-'+y">
                        
                        <!-- THE CELL -->
                        <div 
                            class="w-[40px] h-[40px] border-b border-r border-black relative transition-all duration-75 flex items-center justify-center"
                            :class="{ 
                                'cursor-crosshair': activeTool === 'draw_object' || activeTool === 'draw_container',
                                'cursor-pointer': activeTool && activeTool !== 'draw_object' && activeTool !== 'draw_container',
                                'hover:bg-gray-200 dark:hover:bg-gray-700': activeTool && !isDrawing && !isMoving,
                            }"
                            :style="'background-color: ' + getCellColor(x-1, y-1)"
                            @mousedown.prevent="startAction(x-1, y-1)"
                            @mouseenter="dragAction(x-1, y-1)"
                            @click="cellClicked(x-1, y-1)"
                            @dblclick="cellDblClicked(x-1, y-1)"
                        >
                            
                            <!-- Indikator nomor di tengah kotak secara besar -->
                            <template x-if="isTopLeftCell(x-1, y-1)">
                                <span class="absolute text-xl font-black text-black drop-shadow-[0_0_2px_rgba(255,255,255,1)] z-20 pointer-events-none" x-text="getZoneIndex(x-1, y-1)"></span>
                            </template>
                            
                            <!-- Outline tebal mengelilingi zone yang sudah disimpan -->
                            <div x-show="getZoneTypeAt(x-1, y-1) !== null && !isPartOfDrawing(x-1, y-1)" 
                                    class="absolute inset-0 pointer-events-none z-10 border-black dark:border-white"
                                    :class="{
                                        'border-t-[3px]': !isSameZone(x-1, y-1, 0, -1),
                                        'border-b-[3px]': !isSameZone(x-1, y-1, 0, 1),
                                        'border-l-[3px]': !isSameZone(x-1, y-1, -1, 0),
                                        'border-r-[3px]': !isSameZone(x-1, y-1, 1, 0),
                                        'border-dashed': getZoneTypeAt(x-1, y-1) === 'container',
                                        'border-solid': getZoneTypeAt(x-1, y-1) === 'object'
                                    }">
                            </div>

                            <!-- Outline tebal saat sedang menggambar area baru -->
                            <div x-show="isPartOfDrawing(x-1, y-1)" 
                                    class="absolute inset-0 pointer-events-none z-10 border-black dark:border-white"
                                    :class="{
                                        'border-t-[3px]': !isSameZone(x-1, y-1, 0, -1),
                                        'border-b-[3px]': !isSameZone(x-1, y-1, 0, 1),
                                        'border-l-[3px]': !isSameZone(x-1, y-1, -1, 0),
                                        'border-r-[3px]': !isSameZone(x-1, y-1, 1, 0),
                                        'border-dashed': drawingType === 'container',
                                        'border-solid': drawingType === 'object'
                                    }">
                            </div>
                        </div>

                    </template>
                </template>
            </div>
        </div>

        <!-- Color Picker Modal (Dipindah ke dalam x-data agar berfungsi) -->
        <div x-show="showColorPicker" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div @click.away="showColorPicker = false; $dispatch('color-selected', null)" class="bg-white dark:bg-gray-800 border-4 border-black p-6 shadow-[8px_8px_0px_rgba(0,0,0,1)] w-[400px]">
                <h3 class="text-xl font-black mb-4 text-center text-black dark:text-white uppercase" style="font-family: Impact, sans-serif;">PILIH WARNA</h3>
                
                <!-- Native Color Input for Custom Colors -->
                <div class="mb-6 flex flex-col items-center">
                    <label class="text-sm font-bold mb-2">Bebas Pilih Warna:</label>
                    <div id="pickr-container" class="w-full flex justify-center"></div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-6">
                    <button @click="$dispatch('color-selected', customColor); showColorPicker = false" class="w-full py-3 font-black text-black bg-[#84CC16] border-4 border-black hover:brightness-110 shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-x-1 active:translate-y-1 active:shadow-none uppercase">TERAPKAN WARNA</button>
                    <button @click="showColorPicker = false; $dispatch('color-selected', null)" class="w-full py-3 font-black text-white bg-[#EF4444] border-4 border-black hover:brightness-110 shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-x-1 active:translate-y-1 active:shadow-none">BATAL</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Logika -->
    <script>
        function visualMap() {
            return {
                gridCols: 25,
                gridRows: 15,
                zones: @json($zones),
                activeTool: null,
                instructionText: 'Pilih tool Edit.',
                loading: false,
                
                // Drawing state
                isDrawing: false,
                drawingCells: [],
                drawingType: null,

                // Move state
                isMoving: false,
                movingZoneId: null,
                moveStartX: 0,
                moveStartY: 0,
                moveCurrentX: 0,
                moveCurrentY: 0,
                
                showColorPicker: false,
                customColor: '#84CC16',
                showConfirmation: false,
                pendingZoneUpdateId: null,

                cancelDrawing() {
                    this.drawingCells = [];
                    this.showConfirmation = false;
                    this.isDrawing = false;
                },

                initMap() {
                    // Initialize Pickr
                    this.$nextTick(() => {
                        this.pickrInstance = Pickr.create({
                            el: '#pickr-container',
                            theme: 'nano', // or 'monolith', or 'classic'
                            default: this.customColor,
                            showAlways: true,
                            inline: true,
                            swatches: [
                                '#EF4444', '#F97316', '#F59E0B', '#84CC16', '#10B981', '#06B6D4', 
                                '#0EA5E9', '#3B82F6', '#6366F1', '#8B5CF6', '#A855F7', '#D946EF', 
                                '#EC4CC9', '#F43F5E', '#94A3B8', '#1E293B'
                            ],
                            components: {
                                preview: true,
                                opacity: false,
                                hue: true,
                                interaction: {
                                    hex: true,
                                    rgba: false,
                                    hsla: false,
                                    hsva: false,
                                    cmyk: false,
                                    input: true,
                                    clear: false,
                                    save: false
                                }
                            }
                        });

                        this.pickrInstance.on('change', (color) => {
                            this.customColor = color.toHEXA().toString();
                        });
                    });

                    window.addEventListener('color-selected', (e) => {
                        let color = e.detail;
                        if (!color) {
                            const colors = ['#EF4444', '#10B981', '#3B82F6', '#F59E0B', '#A855F7', '#06B6D4'];
                            color = colors[Math.floor(Math.random() * colors.length)];
                        }
                        
                        if (this.pendingZoneUpdateId) {
                            this.commitUpdateZone(this.pendingZoneUpdateId, { color: color });
                            this.pendingZoneUpdateId = null;
                        } else {
                            this.finalizeDraw(color);
                        }
                    });
                },

                toggleTool(tool) {
                    // Cegah ganti tool jika sedang ada konfirmasi penyimpanan area
                    if (this.showConfirmation) {
                        alert("Selesaikan penyimpanan area terlebih dahulu (Pilih YA atau BATAL).");
                        return;
                    }

                    if (this.activeTool === tool) {
                        this.activeTool = null;
                        this.instructionText = 'Pilih tool Edit.';
                    } else {
                        this.activeTool = tool;
                        const texts = {
                            'color': 'Klik area untuk mengubah warnanya.',
                            'draw_object': 'Klik & Tahan (Drag) untuk menggambar objek.',
                            'draw_container': 'Klik & Tahan (Drag) untuk menggambar container.',
                            'move': 'Klik area lalu Drag ke posisi baru.',
                            'text': 'Klik area untuk mengganti nama.',
                            'trash': 'Klik area untuk menghapus.'
                        };
                        this.instructionText = texts[tool];
                    }
                    this.isDrawing = false;
                    this.isMoving = false;
                    this.drawingCells = [];
                },

                getZoneAt(x, y) {
                    // Cari di temporary move
                    if (this.isMoving && this.movingZoneId) {
                        let zone = this.zones.find(z => z.id === this.movingZoneId);
                        let dx = this.moveCurrentX - this.moveStartX;
                        let dy = this.moveCurrentY - this.moveStartY;
                        
                        let isMovedCell = zone.cells.some(c => (parseInt(c.x) + dx) === x && (parseInt(c.y) + dy) === y);
                        if (isMovedCell) return zone;
                        
                        // Sembunyikan posisi asli saat digeser
                        if (zone.cells.some(c => parseInt(c.x) === x && parseInt(c.y) === y)) return null;
                    }

                    // Cari di data normal
                    for (let z of this.zones) {
                        for (let c of z.cells) {
                            if (parseInt(c.x) === x && parseInt(c.y) === y) return z;
                        }
                    }
                    return null;
                },

                getZoneTypeAt(x, y) {
                    let zone = this.getZoneAt(x, y);
                    return zone ? zone.type : null;
                },

                getZoneIndex(x, y) {
                    let zone = this.getZoneAt(x, y);
                    if (!zone) return '';
                    // Cari index zone
                    let idx = this.zones.findIndex(z => z.id === zone.id);
                    // Cek apakah cell ini adalah cell paling "kiri atas" dari zone tersebut
                    let minX = Math.min(...zone.cells.map(c => parseInt(c.x)));
                    let minY = Math.min(...zone.cells.filter(c => parseInt(c.x) === minX).map(c => parseInt(c.y)));
                    
                    if (this.isMoving && this.movingZoneId === zone.id) {
                        let dx = this.moveCurrentX - this.moveStartX;
                        let dy = this.moveCurrentY - this.moveStartY;
                        if (x === (minX + dx) && y === (minY + dy)) return idx + 1;
                        return '';
                    }

                    if (x === minX && y === minY) return idx + 1;
                    return '';
                },

                isPartOfDrawing(x, y) {
                    return this.drawingCells.some(c => c.x === x && c.y === y);
                },

                getCellColor(x, y) {
                    if (this.isPartOfDrawing(x, y)) {
                        return this.drawingType === 'object' ? '#374151' : '#93C5FD'; // Warna saat proses gambar
                    }
                    let zone = this.getZoneAt(x, y);
                    return zone ? zone.color : 'transparent';
                },

                // --- MOUSE EVENTS --- //

                startAction(x, y) {
                    if (this.activeTool === 'draw_object' || this.activeTool === 'draw_container') {
                        if (this.getZoneAt(x, y) !== null) return; // Tabrak
                        this.isDrawing = true;
                        this.drawingType = this.activeTool === 'draw_object' ? 'object' : 'container';
                        this.drawingCells = [{x, y}];
                    } else if (this.activeTool === 'move') {
                        let zone = this.getZoneAt(x, y);
                        if (zone) {
                            this.isMoving = true;
                            this.movingZoneId = zone.id;
                            this.moveStartX = x;
                            this.moveStartY = y;
                            this.moveCurrentX = x;
                            this.moveCurrentY = y;
                        }
                    }
                },

                dragAction(x, y) {
                    if (this.isDrawing) {
                        if (this.getZoneAt(x, y) === null && !this.isPartOfDrawing(x, y)) {
                            this.drawingCells.push({x, y});
                        }
                    } else if (this.isMoving) {
                        this.moveCurrentX = x;
                        this.moveCurrentY = y;
                    }
                },

                endAction() {
                    if (this.isDrawing) {
                        this.isDrawing = false;
                        if (this.drawingCells.length > 0) {
                            this.pendingZoneUpdateId = null;
                            this.showConfirmation = true;
                        }
                    }

                    if (this.isMoving) {
                        this.isMoving = false;
                        if (this.movingZoneId) {
                            let dx = this.moveCurrentX - this.moveStartX;
                            let dy = this.moveCurrentY - this.moveStartY;
                            if (dx !== 0 || dy !== 0) {
                                this.commitMove(this.movingZoneId, dx, dy);
                            }
                        }
                        this.movingZoneId = null;
                    }
                },

                // --- CLICKS UNTUK TOOL LAIN --- //

                cellClicked(x, y) {
                    let zone = this.getZoneAt(x, y);
                    if (!zone) return;

                    if (this.activeTool === 'trash') {
                        this.deleteZone(zone.id);
                    } else if (this.activeTool === 'text') {
                        let newName = prompt('Ubah Nama Area (saat ini: ' + (zone.name || '?') + '):', zone.name || '');
                        if (newName !== null) {
                            this.commitUpdateZone(zone.id, { name: newName });
                        }
                    } else if (this.activeTool === 'color') {
                        this.pendingZoneUpdateId = zone.id;
                        this.customColor = zone.color || '#84CC16'; // Set default picker color to current zone color
                        if (this.pickrInstance) {
                            this.pickrInstance.setColor(this.customColor);
                        }
                        this.showColorPicker = true;
                    }
                },

                cellDblClicked(x, y) {
                    let zone = this.getZoneAt(x, y);
                    if (zone && zone.type === 'container' && !this.activeTool) {
                        window.location.href = '/visual-inventory/' + zone.id;
                    }
                },

                // --- API COMMITS --- //

                async finalizeDraw(color) {
                    if (this.drawingCells.length === 0) return;
                    this.loading = true;
                    try {
                        let res = await fetch('/api/visual-inventory/zones', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                            },
                            body: JSON.stringify({
                                type: this.drawingType,
                                color: color,
                                cells: this.drawingCells
                            })
                        });
                        let data = await res.json();
                        if (data.success) {
                            // Fix Reactivity: Reassign array instead of push
                            this.zones = [...this.zones, data.zone];
                            
                            // Auto-prompt untuk nama area setelah warna dipilih
                            setTimeout(() => {
                                let newName = prompt('Area berhasil disimpan! Masukkan deskripsi area ini (misal: Pagar Kuning):');
                                if (newName) {
                                    this.commitUpdateZone(data.zone.id, { name: newName });
                                }
                            }, 100);
                        } else {
                            alert(data.message || 'Gagal menyimpan.');
                        }
                    } catch (err) {
                        alert('Terjadi kesalahan jaringan.');
                    }
                    this.drawingCells = [];
                    this.loading = false;
                },

                async commitUpdateZone(id, payload) {
                    this.loading = true;
                    try {
                        let res = await fetch('/api/visual-inventory/zones/' + id, {
                            method: 'PUT',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                            },
                            body: JSON.stringify(payload)
                        });
                        let data = await res.json();
                        if (data.success) {
                            let idx = this.zones.findIndex(z => z.id === id);
                            if (idx > -1) {
                                // Fix Reactivity
                                let temp = [...this.zones];
                                temp[idx] = data.zone;
                                this.zones = temp;
                            }
                        }
                    } catch (err) {}
                    this.loading = false;
                },

                async deleteZone(id) {
                    if (!confirm('Yakin ingin menghapus area ini?')) return;
                    this.loading = true;
                    try {
                        let res = await fetch('/api/visual-inventory/zones/' + id, {
                            method: 'DELETE',
                            headers: { 
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                            }
                        });
                        let data = await res.json();
                        if (data.success) {
                            this.zones = this.zones.filter(z => z.id !== id);
                        } else {
                            alert(data.message);
                        }
                    } catch (err) {}
                    this.loading = false;
                },

                async commitMove(id, dx, dy) {
                    this.loading = true;
                    try {
                        let res = await fetch('/api/visual-inventory/zones/' + id + '/move', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                            },
                            body: JSON.stringify({ dx, dy })
                        });
                        let data = await res.json();
                        if (data.success) {
                            let idx = this.zones.findIndex(z => z.id === id);
                            if (idx > -1) {
                                // Fix Reactivity
                                let temp = [...this.zones];
                                temp[idx] = data.zone;
                                this.zones = temp;
                            }
                        } else {
                            alert(data.message); // Tabrakan / Out of bounds
                        }
                    } catch (err) {}
                    
                    // Kembalikan view
                    this.moveCurrentX = this.moveStartX;
                    this.moveCurrentY = this.moveStartY;
                    this.loading = false;
                }
            }
        }
    </script>
</x-layouts.app>