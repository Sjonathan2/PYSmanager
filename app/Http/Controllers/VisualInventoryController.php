<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\ZoneCell;
use App\Models\StorageBox;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisualInventoryController extends Controller
{
    // --- 1. TAMPILAN MAP UTAMA (GAMBAR 1) ---
    public function index()
    {
        // Load all zones with their cells to render the map
        $zones = Zone::with('cells')->get();
        return view('visual-inventory.index', compact('zones'));
    }

    // API: Simpan/Buat Zone Baru
    public function storeZone(Request $request)
    {
        $request->validate([
            'type' => 'required|in:object,container',
            'color' => 'required|string',
            'cells' => 'required|array',
            'cells.*.x' => 'required|integer',
            'cells.*.y' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $zone = Zone::create([
                'type' => $request->type,
                'color' => $request->color,
                'name' => null, // Name diisi nanti via edit
            ]);

            foreach ($request->cells as $cell) {
                ZoneCell::create([
                    'zone_id' => $zone->id,
                    'x' => $cell['x'],
                    'y' => $cell['y'],
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'zone' => $zone->load('cells')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    // API: Update Zone (Warna / Nama)
    public function updateZone(Request $request, Zone $zone)
    {
        $request->validate([
            'name' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        if ($request->has('name')) {
            $zone->name = $request->name;
        }
        if ($request->has('color')) {
            $zone->color = $request->color;
        }
        
        $zone->save();
        return response()->json(['success' => true, 'zone' => $zone->load('cells')]);
    }

    // API: Pindahkan Zone (Tetris Move)
    public function moveZone(Request $request, Zone $zone)
    {
        $request->validate([
            'dx' => 'required|integer', // Delta X
            'dy' => 'required|integer', // Delta Y
        ]);

        DB::beginTransaction();
        try {
            // Check collision with other zones
            $cells = $zone->cells;
            foreach ($cells as $cell) {
                $newX = $cell->x + $request->dx;
                $newY = $cell->y + $request->dy;

                // Pastikan tidak out of bounds (misal max grid 20x20, tapi batas bisa ditaruh di UI)
                if ($newX < 0 || $newY < 0) {
                     throw new \Exception("Out of bounds");
                }

                // Check tabrakan dengan sel dari zone lain
                $collision = ZoneCell::where('x', $newX)
                                     ->where('y', $newY)
                                     ->where('zone_id', '!=', $zone->id)
                                     ->exists();
                if ($collision) {
                    throw new \Exception("Area bertabrakan dengan objek lain.");
                }
            }

            // Apply movement
            foreach ($cells as $cell) {
                $cell->x += $request->dx;
                $cell->y += $request->dy;
                $cell->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'zone' => $zone->load('cells')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    // API: Hapus Zone
    public function destroyZone(Zone $zone)
    {
        // Aturan: Jika zone ini container dan punya storage box yang berisi stok, tidak bisa dihapus.
        if ($zone->type === 'container') {
            $hasStock = $zone->storageBoxes()->whereHas('placements', function($query) {
                $query->where('quantity', '>', 0);
            })->exists();

            if ($hasStock) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa dihapus! Container ini masih berisi stok jaket.'], 400);
            }
        }

        $zone->delete();
        return response()->json(['success' => true]);
    }


    // --- 2. TAMPILAN DETAIL CONTAINER (GAMBAR 2) ---
    public function showContainer(Zone $zone)
    {
        if ($zone->type !== 'container') {
            abort(404);
        }

        $zone->load(['storageBoxes.placements.productVariant.product']);
        $productVariants = ProductVariant::with('product')->get(); // Untuk dropdown pemilihan stok

        return view('visual-inventory.container', compact('zone', 'productVariants'));
    }

    // Dan seterusnya untuk Box API (nanti akan saya lengkapi di file ini juga)
}
