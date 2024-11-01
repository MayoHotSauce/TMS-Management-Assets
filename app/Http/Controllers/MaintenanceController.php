<?php

// app/Http/Controllers/MaintenanceController.php
namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Barang;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenanceLogs = MaintenanceLog::with('barang')
            ->orderBy('maintenance_date', 'desc')
            ->paginate(10);
            
        return view('maintenance.index', compact('maintenanceLogs'));
    }
    
    public function create()
    {
        $assets = Barang::all();
        return view('maintenance.create', compact('assets'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:daftar_barang,id',
            'maintenance_date' => 'required|date',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'performed_by' => 'required|string',
            'status' => 'required|in:completed,pending,scheduled'
        ]);
        
        $maintenanceLog = MaintenanceLog::create($validated);
        
        // Update asset maintenance dates
        $asset = Barang::find($request->barang_id);
        if ($request->status === 'completed') {
            $asset->last_maintenance = $request->maintenance_date;
            $asset->next_maintenance = date('Y-m-d', strtotime($request->maintenance_date . ' + 6 months'));
            $asset->condition = 'good';
            $asset->status = 'active';
            $asset->save();
        }
        
        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance log created successfully');
    }
}