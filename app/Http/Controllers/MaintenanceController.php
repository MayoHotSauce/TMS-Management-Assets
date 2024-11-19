<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\MaintenanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        // Ambil status dari request, default ke 'active'
        $currentStatus = $request->get('status', 'active');
        
        $query = MaintenanceLog::with('asset')->latest();
        
        // Filter berdasarkan status
        switch ($currentStatus) {
            case 'active':
                $query->whereIn('status', ['scheduled', 'pending']);
                break;
            case 'completed':
                $query->where('status', 'completed');
                break;
            case 'all':
                // Tidak perlu filter untuk 'all'
                break;
        }

        $maintenanceLogs = $query->paginate(10)->withQueryString();
        $assets = Asset::where('status', '!=', 'dalam_perbaikan')->get();

        return view('maintenance.index', compact('maintenanceLogs', 'assets', 'currentStatus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:assets,id',
            'description' => 'required|string',
            'maintenance_date' => 'required|date',
            'cost' => 'required|numeric',
            'performed_by' => 'required|string',
            'status' => 'required|in:scheduled,pending,completed'
        ]);

        DB::transaction(function () use ($validated) {
            // Buat maintenance log
            $maintenance = MaintenanceLog::create($validated);

            // Update status asset berdasarkan status maintenance
            $assetStatus = match($validated['status']) {
                'scheduled' => 'dalam_perbaikan',
                'pending' => 'dalam_perbaikan',
                'completed' => 'siap_dipakai',
                default => 'siap_dipakai'
            };

            Asset::where('id', $validated['barang_id'])
                ->update(['status' => $assetStatus]);
        });

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance log created successfully');
    }

    public function update(Request $request, MaintenanceLog $maintenance)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,pending,completed'
        ]);

        DB::transaction(function () use ($maintenance, $validated) {
            $maintenance->update([
                'status' => $validated['status']
            ]);

            // Update status asset berdasarkan status maintenance baru
            $assetStatus = match($validated['status']) {
                'scheduled' => 'dalam_perbaikan',
                'pending' => 'dalam_perbaikan',
                'completed' => 'siap_dipakai',
                default => 'siap_dipakai'
            };

            Asset::where('id', $maintenance->barang_id)
                ->update(['status' => $assetStatus]);
        });

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance status updated successfully');
    }

    public function destroy($id)
    {
        $maintenance = MaintenanceLog::findOrFail($id);
        $maintenance->delete();

        return redirect()->route('maintenance.index')
                        ->with('success', 'Maintenance log deleted successfully');
    }

    public function complete(MaintenanceLog $maintenance)
    {
        DB::transaction(function () use ($maintenance) {
            $maintenance->update([
                'status' => 'completed'
            ]);

            Asset::where('id', $maintenance->barang_id)
                ->update(['status' => 'siap_dipakai']);
        });

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance marked as completed');
    }

    public function updateStatus(Request $request, MaintenanceLog $maintenance)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending'
        ]);

        DB::transaction(function () use ($maintenance, $validated) {
            $maintenance->update([
                'status' => $validated['status']
            ]);

            // Update status asset menjadi dalam_perbaikan
            Asset::where('id', $maintenance->barang_id)
                ->update(['status' => 'dalam_perbaikan']);
        });

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance status updated to In Progress');
    }
}