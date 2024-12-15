<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\BarangSequence;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;

class DataBarangController extends Controller
{
    public function index()
    {
        $barang = Asset::with(['category', 'room'])->paginate(10);
        $categories = Category::all();
        
        ActivityLogger::log(
            'view',
            'barang',
            'Viewed barang list',
            null,
            ['total_items' => $barang->total()]
        );
        
        return view('barang.index', compact('barang', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $rooms = Room::all();
        
        ActivityLogger::log(
            'view',
            'barang',
            'Accessed barang creation form',
            null,
            ['available_categories' => $categories->count(), 'available_rooms' => $rooms->count()]
        );
        
        return view('barang.create', compact('categories', 'rooms'));
    }

    private function generateId($roomId)
    {
        $sequence = BarangSequence::first();
        $nextId = $sequence->next_val;
        
        // Update sequence
        $sequence->next_val = $nextId + 1;
        $sequence->save();
        
        // Generate simpler asset tag without room code
        return "TMS-" . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
            'category_id' => 'required|exists:categories,id',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:0'
        ]);

        $validated['purchase_date'] = date('Y-m-d', strtotime($request->purchase_date));
        $validated['status'] = 'siap_dipakai';
        $validated['asset_tag'] = $this->generateId($request->room_id);
        
        DB::beginTransaction();
        try {
            $barang = Asset::create($validated);

            ActivityLogger::log(
                'create',
                'barang',
                'Added new barang: ' . $barang->name,
                null,
                $barang->toArray()
            );

            DB::commit();
            return redirect()->route('barang.index')
                            ->with('success', 'Barang added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->with('error', 'Error creating asset: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $barang = Asset::findOrFail($id);
        $categories = Category::all();
        $rooms = Room::all();
        
        ActivityLogger::log(
            'view',
            'barang',
            'Accessed edit form for barang: ' . $barang->name,
            null,
            $barang->toArray()
        );
        
        return view('barang.edit', compact('barang', 'categories', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $barang = Asset::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
            'category_id' => 'required|exists:categories,id',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:0'
        ]);

        $oldValues = $barang->toArray();
        $oldRoom = $barang->room->name;
        $oldCategory = $barang->category->name;
        
        $barang->update($validated);
        $barang->refresh();

        $changes = [];
        if ($oldValues['name'] !== $barang->name) {
            $changes[] = "name from '{$oldValues['name']}' to '{$barang->name}'";
        }
        if ($oldValues['description'] !== $barang->description) {
            $changes[] = "description from '{$oldValues['description']}' to '{$barang->description}'";
        }
        if ($oldRoom !== $barang->room->name) {
            $changes[] = "room from '{$oldRoom}' to '{$barang->room->name}'";
        }
        if ($oldCategory !== $barang->category->name) {
            $changes[] = "category from '{$oldCategory}' to '{$barang->category->name}'";
        }
        if ($oldValues['purchase_cost'] !== $barang->purchase_cost) {
            $changes[] = "purchase cost from '{$oldValues['purchase_cost']}' to '{$barang->purchase_cost}'";
        }

        ActivityLogger::log(
            'update',
            'barang',
            'Updated barang: ' . implode(', ', $changes),
            $oldValues,
            $barang->toArray()
        );

        return redirect()->route('barang.index')
            ->with('success', 'Barang updated successfully');
    }

    public function destroy($id)
    {
        $barang = Asset::findOrFail($id);
        $barangName = $barang->name;
        $oldValues = $barang->toArray();
        
        $barang->delete();

        ActivityLogger::log(
            'delete',
            'barang',
            'Deleted barang: ' . $barangName,
            $oldValues,
            null
        );

        return redirect()->route('barang.index')
                        ->with('success', 'Barang deleted successfully');
    }
}