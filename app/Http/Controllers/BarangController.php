<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangSequence;
use Illuminate\Http\Request;
use DB;

class BarangController extends Controller
{
    private function generateId($room)
    {
        $sequence = BarangSequence::first();
        $nextId = $sequence->next_val;
        
        // Update sequence
        $sequence->next_val = $nextId + 1;
        $sequence->save();
        
        return "TMS-" . strtoupper($room) . "-" . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        $barang = Barang::with('category')->paginate(10);
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        $categories = DB::table('categories')->get();
        return view('barang.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'room' => 'required|string',
            'category_id' => 'required|integer',
            'tahun_pengadaan' => 'required|integer'
        ]);

        $validated['id'] = $this->generateId($request->room);
        
        Barang::create($validated);
        return redirect()->route('barang.index')->with('success', 'Asset added successfully');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $categories = DB::table('categories')->get();
        return view('barang.edit', compact('barang', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'room' => 'required|string',
            'category_id' => 'required|integer',
            'tahun_pengadaan' => 'required|integer'
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($validated);
        
        return redirect()->route('barang.index')->with('success', 'Asset updated successfully');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();
        
        return redirect()->route('barang.index')->with('success', 'Asset deleted successfully');
    }
}