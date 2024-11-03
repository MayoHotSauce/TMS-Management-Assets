<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenanceLogs = DB::table('maintenance_logs')
            ->leftJoin('assets', 'maintenance_logs.barang_id', '=', 'assets.id')
            ->select('maintenance_logs.*', 'assets.name as asset_name')
            ->get();

        return view('maintenance.index', compact('maintenanceLogs'));
    }

    public function create()
    {
        $assets = Asset::all();
        return view('maintenance.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:assets,id',
            'maintenance_date' => 'required|date',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric',
            'performed_by' => 'nullable|string',
            'status' => 'required|string',
        ]);

        Maintenance::create($request->all());

        return redirect()->route('maintenance.index')->with('success', 'Maintenance log created successfully.');
    }

    public function edit($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $assets = Asset::all();

        return view('maintenance.edit', compact('maintenance', 'assets'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'maintenance_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'performed_by' => 'required|string|max:255',
            'status' => 'required|in:pending,completed,scheduled'
        ]);

        $maintenance = Maintenance::findOrFail($id);
        $maintenance->update($request->all());

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance log updated successfully');
    }

    public function destroy($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance log deleted successfully');
    }
}