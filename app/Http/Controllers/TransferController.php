<?php

// app/Http/Controllers/TransferController.php
namespace App\Http\Controllers;

use App\Models\AssetTransfer;
use App\Models\Barang;
use App\Models\Room;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        $transfers = AssetTransfer::with('barang')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('transfers.index', compact('transfers'));
    }
    
    public function create()
    {
        $assets = Barang::all();
        $rooms = Room::all();
        return view('transfers.create', compact('assets', 'rooms'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:daftar_barang,id',
            'to_room' => 'required|exists:rooms,name',
            'transfer_date' => 'required|date',
            'reason' => 'required|string'
        ]);
        
        $asset = Barang::find($request->barang_id);
        $validated['from_room'] = $asset->room;
        $validated['status'] = 'pending';
        
        AssetTransfer::create($validated);
        
        return redirect()->route('transfers.index')
            ->with('success', 'Transfer request created successfully');
    }
    
    public function approve(Request $request, AssetTransfer $transfer)
    {
        $transfer->status = 'approved';
        $transfer->approved_by = auth()->user()->name;
        $transfer->save();
        
        // Update asset location
        $asset = $transfer->barang;
        $asset->room = $transfer->to_room;
        $asset->save();
        
        return redirect()->route('transfers.index')
            ->with('success', 'Transfer approved successfully');
    }
}