<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\MaintenanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $currentStatus = $request->get('status', 'active'); // Default to 'active' if not set

        $maintenanceLogs = MaintenanceLog::with('asset')
            ->whereIn('status', ['in_progress', 'scheduled', 'pending_completion', 'pending_final_approval'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('maintenance.index', compact('maintenanceLogs', 'currentStatus'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'barang_id' => 'required|exists:assets,id',
                'maintenance_date' => 'required|date',
                'description' => 'required|string',
                'cost' => 'required|numeric',
                'performed_by' => 'required|string'
            ]);

            \Log::info('Request data:', $request->all());

            DB::beginTransaction();

            $maintenance = new MaintenanceLog();
            $maintenance->barang_id = $validated['barang_id'];
            $maintenance->maintenance_date = $validated['maintenance_date'];
            $maintenance->description = $validated['description'];
            $maintenance->cost = $validated['cost'];
            $maintenance->performed_by = $validated['performed_by'];
            $maintenance->status = 'scheduled';
            
            \Log::info('Maintenance object before save:', $maintenance->toArray());
            
            $maintenance->save();
            
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Maintenance log berhasil dibuat'
                ]);
            }

            return redirect()->route('maintenance.index')
                ->with('success', 'Maintenance log berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in maintenance creation:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
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

    public function complete(Request $request, Maintenance $maintenance)
    {
        try {
            $validated = $request->validate([
                'completion_date' => 'required|date',
                'actions_taken' => 'required|string',
                'results' => 'required|string',
                'replaced_parts' => 'nullable|string',
                'total_cost' => 'required|numeric',
                'equipment_status' => 'required|string',
                'recommendations' => 'nullable|string',
                'additional_notes' => 'nullable|string',
                'technician_name' => 'required|string',
                'follow_up_priority' => 'required|string'
            ]);

            // Update maintenance record with completion details
            $maintenance->update([
                'completion_date' => $validated['completion_date'],
                'actions_taken' => $validated['actions_taken'],
                'results' => $validated['results'],
                'replaced_parts' => $validated['replaced_parts'],
                'total_cost' => $validated['total_cost'],
                'equipment_status' => $validated['equipment_status'],
                'recommendations' => $validated['recommendations'],
                'additional_notes' => $validated['additional_notes'],
                'technician_name' => $validated['technician_name'],
                'follow_up_priority' => $validated['follow_up_priority'],
                'status' => 'pending_final_approval' // Important: Update status to pending final approval
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Maintenance completion recorded successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
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

    public function showCompletionForm($id)
    {
        $maintenance = MaintenanceLog::findOrFail($id);
        return view('maintenance.completion_form', compact('maintenance'));
    }

    public function submitCompletion(Request $request, $id)
    {
        $maintenance = MaintenanceLog::findOrFail($id);
        
        $validated = $request->validate([
            'completion_date' => 'required|date',
            'technician' => 'required|string',
            'actions_taken' => 'required|string',
            'repair_result' => 'required|string',
            'replaced_parts' => 'nullable|string',
            'total_cost' => 'required|numeric',
            'equipment_status' => 'nullable|string',
            'next_priority' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'additional_notes' => 'nullable|string'
        ]);

        $maintenance->update([
            'completion_date' => $validated['completion_date'],
            'technician' => $validated['technician'],
            'actions_taken' => $validated['actions_taken'],
            'repair_result' => $validated['repair_result'],
            'replaced_parts' => $validated['replaced_parts'],
            'total_cost' => $validated['total_cost'],
            'equipment_status' => $validated['equipment_status'],
            'next_priority' => $validated['next_priority'],
            'recommendations' => $validated['recommendations'],
            'additional_notes' => $validated['additional_notes'],
            'status' => 'pending_final_approval'
        ]);

        return redirect()->route('maintenance.index')
            ->with('success', 'Formulir penyelesaian telah disetujui untuk persetujuan akhir');
    }

    public function approvalList()
    {
        // For initial approvals (new maintenance requests)
        $initialApprovals = MaintenanceLog::where('approval_status', 'pending')
            ->with('asset')
            ->paginate(10);

        // For final approvals (completed maintenance waiting for final approval)
        $finalApprovals = MaintenanceLog::where('status', 'pending_final_approval')
            ->with('asset')
            ->paginate(10);

        // Debug information
        \Log::info('Initial Approvals Count: ' . $initialApprovals->count());
        \Log::info('Final Approvals Count: ' . $finalApprovals->count());

        return view('maintenance.approval_list', compact('initialApprovals', 'finalApprovals'));
    }

    public function approve($id)
    {
        try {
            $maintenance = MaintenanceLog::findOrFail($id);
            $message = '';
            
            DB::beginTransaction();
            
            // Debug current status
            \Log::info('Current status: ' . $maintenance->status);
            \Log::info('Current approval_status: ' . $maintenance->approval_status);
            
            // Check if maintenance is already completed
            if ($maintenance->status === 'completed') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Status perbaikan saat ini: completed. Tidak dapat diproses.'
                ]);
            }
            
            // Initial approval
            if ($maintenance->status === 'scheduled' || $maintenance->status === 'pending') {
                $maintenance->update([
                    'status' => 'in_progress',
                    'approval_status' => 'approved'
                ]);
                $message = 'Persetujuan berhasil. Perbaikan dapat dimulai.';
            }
            // Final approval
            else if ($maintenance->status === 'pending_final_approval') {
                $maintenance->update([
                    'status' => 'completed',
                    'approval_status' => 'final_approved'
                ]);
                $message = 'Persetujuan akhir berhasil. Perbaikan telah selesai.';
            }
            else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Status perbaikan saat ini: ' . $maintenance->status . '. Tidak dapat diproses.'
                ]);
            }
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $maintenance = MaintenanceLog::with('asset')->findOrFail($id);
        return view('maintenance.show', compact('maintenance'));
    }

    public function create()
    {
        $assets = Asset::all();
        return view('maintenance.create', compact('assets'));
    }

    public function completeExisting(Request $request, $id)
    {
        $maintenance = MaintenanceLog::findOrFail($id);

        $validated = $request->validate([
            'actions_taken' => 'required|string',
            'results' => 'required|string',
            'replaced_parts' => 'nullable|string',
            'total_cost' => 'required|numeric',
            'recommendations' => 'nullable|string'
        ]);

        $maintenance->update([
            'actions_taken' => $validated['actions_taken'],
            'results' => $validated['results'],
            'replaced_parts' => $validated['replaced_parts'],
            'total_cost' => $validated['total_cost'],
            'recommendations' => $validated['recommendations'],
            'status' => 'pending_final_approval'
        ]);

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance completion form submitted for final approval');
    }

    public function startWork($id)
    {
        $maintenance = MaintenanceLog::findOrFail($id);
        $maintenance->status = 'in_progress';
        $maintenance->save();

        return response()->json(['success' => true]);
    }

    public function showApprovalDetail($id)
    {
        $maintenance = MaintenanceLog::with('asset')->findOrFail($id);
        return view('maintenance.approval_detail', compact('maintenance'));
    }
}