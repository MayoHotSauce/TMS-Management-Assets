<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\MaintenanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceLog::with('asset')->latest();
        
        // Default to 'active' if no status is specified
        $currentStatus = $request->status ?? 'active';
        
        // Handle status filtering
        switch($currentStatus) {
            case 'active':
                $query->whereIn('status', ['scheduled', 'pending']);
                break;
            case 'completed':
                $query->where('status', 'completed');
                break;
            // 'all' will show everything
        }

        // Add pagination with 4 items per page
        $maintenanceLogs = $query->paginate(4);
        $assets = Asset::all();

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

        $maintenance = MaintenanceLog::create($validated);

        ActivityLogger::log(
            'create',
            'maintenance',
            'Created new maintenance log for: ' . $maintenance->asset->name,
            null,
            $maintenance->toArray()
        );

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance log created successfully');
    }

    public function update(Request $request, $id)
    {
        $maintenance = MaintenanceLog::findOrFail($id);
        
        $validated = $request->validate([
            'description' => 'required|string',
            'maintenance_date' => 'required|date',
            'cost' => 'required|numeric',
            'performed_by' => 'required|string'
        ]);

        $oldValues = $maintenance->toArray();
        $maintenance->update($validated);

        ActivityLogger::log(
            'update',
            'maintenance',
            'Updated maintenance log for: ' . $maintenance->asset->name,
            $oldValues,
            $maintenance->toArray()
        );

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance log updated successfully');
    }

    public function destroy($id)
    {
        $maintenance = MaintenanceLog::findOrFail($id);
        $oldValues = $maintenance->toArray();
        
        $maintenance->delete();

        ActivityLogger::log(
            'delete',
            'maintenance',
            'Deleted maintenance log for: ' . $maintenance->asset->name,
            $oldValues,
            null
        );

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance log deleted successfully');
    }

    public function complete(Request $request, $id)
    {
        try {
            $maintenance = MaintenanceLog::findOrFail($id);
            $oldValues = $maintenance->toArray();
            
            $request->validate([
                'status' => 'required|in:scheduled,pending,completed'
            ]);

            $maintenance->status = $request->status;
            $maintenance->save();

            ActivityLogger::log(
                'update_status',
                'maintenance',
                'Updated maintenance status to ' . $request->status . ' for: ' . $maintenance->asset->name,
                $oldValues,
                $maintenance->toArray()
            );

            $currentFilter = request('status', 'active');
            return response()->json([
                'message' => 'Status maintenance berhasil diperbarui',
                'redirectUrl' => route('maintenance.index', ['status' => $currentFilter])
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, MaintenanceLog $maintenance)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,pending,completed'
        ]);

        $oldValues = $maintenance->toArray();
        $oldStatus = $maintenance->status;
        
        $maintenance->update(['status' => $validated['status']]);

        ActivityLogger::log(
            'update_status',
            'maintenance',
            "Updated maintenance for '{$maintenance->asset->name}': status changed from '{$oldStatus}' to '{$validated['status']}'",
            $oldValues,
            $maintenance->toArray()
        );

        return redirect()->route('maintenance.index')
            ->with('success', 'Status updated successfully');
    }
}