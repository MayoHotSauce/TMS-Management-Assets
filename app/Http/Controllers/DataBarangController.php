<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\BarangSequence;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataBarangController extends Controller
{
    public function index()
    {
        $barang = Asset::with(['category', 'room'])->paginate(10);
        $categories = Category::all();
        return view('barang.index', compact('barang', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $rooms = Room::all();
        return view('barang.create', compact('categories', 'rooms'));
    }

    private function generateId($roomId)
    {
        $sequence = BarangSequence::first();
        $nextId = $sequence->next_val;
        
        // Update sequence
        $sequence->next_val = $nextId + 1;
        $sequence->save();
        
        // Get room code from room_id
        $room = Room::find($roomId);
        $roomCode = strtoupper(substr($room->name, 0, 2));
        
        return "TMS-" . $roomCode . "-" . str_pad($nextId, 5, '0', STR_PAD_LEFT);
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

        // Format the purchase_date before saving
        $validated['purchase_date'] = date('Y-m-d', strtotime($request->purchase_date));
        
        // Add default values
        $validated['status'] = 'siap_dipakai';
        $validated['asset_tag'] = $this->generateId($request->room_id);
        
        // Create the asset in assets table, not daftar_barang
        $barang = Asset::create($validated);

        return redirect()->route('barang.index')
                        ->with('success', 'Asset added successfully');
    }

    public function edit($id)
    {
        $barang = Asset::findOrFail($id);
        $categories = Category::all();
        $rooms = Room::all();
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

        // Format the purchase_date before saving
        $validated['purchase_date'] = date('Y-m-d', strtotime($request->purchase_date));
        
        $barang->update($validated);
        
        return redirect()->route('barang.index')
                        ->with('success', 'Asset updated successfully');
    }

    public function destroy($id)
    {
        $barang = Asset::findOrFail($id);
        $barang->delete();
        return redirect()->route('barang.index')
                        ->with('success', 'Barang berhasil dihapus');
    }
}