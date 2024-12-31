<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLogger;

class MaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Tambahkan middleware permission
        $this->middleware('permission:view maintenances', ['only' => ['index', 'show']]);
        $this->middleware('permission:create maintenance', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit maintenance', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete maintenance', ['only' => ['destroy']]);
        $this->middleware('permission:approve maintenance', ['only' => ['approve', 'approvalList', 'showApprovalDetail']]);
        $this->middleware('permission:revise maintenance', ['only' => ['reject']]);
        $this->middleware('permission:complete maintenance', ['only' => ['showCompletionForm', 'complete']]);
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'active');
        
        $query = MaintenanceLog::with('asset');
        
        switch ($status) {
            case 'scheduled':
                $query->whereIn('status', ['scheduled', 'pending_final_approval']);
                break;
            case 'completed':
                $query->where('status', 'completed');
                break;
            case 'revisi':
                $query->where('status', 'archived');
                break;
            case 'active':
            default:
                $query->where('status', 'in_progress');
                break;
        }
        
        $maintenances = $query->orderBy('created_at', 'desc')
                             ->paginate(10);
        
        return view('maintenance.index', compact('maintenances', 'status'));
    }

    public function create()
    {
        $assets = Asset::all();
        return view('maintenance.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:assets,id',
            'description' => 'required|string',
            'maintenance_date' => 'required|date',
            'cost' => 'required|numeric',
            'performed_by' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            
            MaintenanceLog::create([
                'barang_id' => $validated['barang_id'],
                'description' => $validated['description'],
                'maintenance_date' => $validated['maintenance_date'],
                'cost' => $validated['cost'],
                'performed_by' => $validated['performed_by'],
                'status' => 'scheduled'
            ]);

            DB::commit();
            return redirect()->route('maintenance.index')
                ->with('success', 'Maintenance log created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating maintenance log: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $maintenance = MaintenanceLog::with('asset')->findOrFail($id);
        return view('maintenance.show', compact('maintenance'));
    }

    public function edit($id)
    {
        $maintenance = MaintenanceLog::findOrFail($id);
        $assets = Asset::all();
        return view('maintenance.edit', compact('maintenance', 'assets'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:assets,id',
            'description' => 'required|string',
            'maintenance_date' => 'required|date',
            'cost' => 'required|numeric',
            'performed_by' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            
            $maintenance = MaintenanceLog::findOrFail($id);
            $maintenance->update($validated);
            
            DB::commit();
            return redirect()->route('maintenance.index')
                ->with('success', 'Maintenance log updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating maintenance log: ' . $e->getMessage());
        }
    }

    public function approvalList()
    {
        $initialApprovals = MaintenanceLog::with(['asset'])
            ->where('status', 'scheduled')
            ->paginate(10);

        $finalApprovals = MaintenanceLog::with(['asset'])
            ->where('status', 'pending_final_approval')
            ->paginate(10);

        return view('maintenance.approval_list', compact('initialApprovals', 'finalApprovals'));
    }

    public function showApprovalDetail($id)
    {
        try {
            $maintenance = MaintenanceLog::with('asset')->findOrFail($id);
            
            return view('maintenance.approval_detail', [
                'maintenance' => $maintenance
            ]);
        } catch (\Exception $e) {
            return redirect()->route('maintenance.approval-list')
                ->with('error', 'Error loading approval detail: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();
            
            $maintenance = MaintenanceLog::findOrFail($id);
            
            // Check if this is a final approval
            if ($maintenance->status === 'pending_final_approval') {
                $maintenance->status = 'completed';
                $maintenance->approval_status = 'approved';
                $maintenance->approval_date = now();
                $successMessage = 'Maintenance telah selesai disetujui';
            } else {
                // This is initial approval
                $maintenance->status = 'in_progress';
                $maintenance->approval_status = 'approved';
                $maintenance->approval_date = now();
                $successMessage = 'Maintenance telah disetujui dan siap dikerjakan';
            }
            
            $maintenance->save();
            
            DB::commit();
            
            return redirect()->route('maintenance.approvals')
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error approving maintenance: ' . $e->getMessage());
        }
    }

    public function finalApprove($id)
    {
        try {
            DB::beginTransaction();
            
            $maintenance = MaintenanceLog::findOrFail($id);
            $maintenance->status = 'completed';
            $maintenance->final_approval_date = now();
            $maintenance->save();
            
            DB::commit();
            
            return redirect()->route('maintenance.approvals')
                ->with('success', 'Maintenance telah selesai disetujui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error pada final approval: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            DB::beginTransaction();
            
            $maintenance = MaintenanceLog::findOrFail($id);
            $maintenance->status = 'rejected';
            $maintenance->approval_date = now();
            $maintenance->save();
            
            DB::commit();
            
            return redirect()->route('maintenance.approval-list')
                ->with('success', 'Maintenance telah ditolak');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error rejecting maintenance: ' . $e->getMessage());
        }
    }

    public function showCompletionForm($id)
    {
        $maintenance = MaintenanceLog::findOrFail($id);
        return view('maintenance.completion_form', compact('maintenance'));
    }

    public function complete(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $maintenance = MaintenanceLog::with('asset')->findOrFail($id);
            
            $validated = $request->validate([
                'equipment_status' => 'required',
                'actions_taken' => 'required',
                'results' => 'required',
                'replaced_parts' => 'nullable',
                'total_cost' => 'required|numeric',
                'follow_up_priority' => 'nullable',
                'recommendations' => 'nullable',
                'additional_notes' => 'nullable',
                'completion_date' => 'required|date',
                'technician_name' => 'required'
            ]);

            // Update maintenance record
            $maintenance->status = 'pending_final_approval';
            $maintenance->equipment_status = $validated['equipment_status'];
            $maintenance->actions_taken = $validated['actions_taken'];
            $maintenance->results = $validated['results'];
            $maintenance->replaced_parts = $validated['replaced_parts'];
            $maintenance->total_cost = $validated['total_cost'];
            $maintenance->recommendations = $validated['recommendations'];
            $maintenance->additional_notes = $validated['additional_notes'];
            $maintenance->completion_date = $validated['completion_date'];
            $maintenance->technician_name = $validated['technician_name'];
            
            // Update asset status if needed
            if ($maintenance->asset) {
                $maintenance->asset->status = $this->mapEquipmentStatusToAssetStatus($validated['equipment_status']);
                $maintenance->asset->save();
            }

            $maintenance->save();
            
            DB::commit();

            return redirect()->route('maintenance.index')
                ->with('success', 'Maintenance completion submitted for final approval');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error submitting completion: ' . $e->getMessage());
        }
    }

    private function mapEquipmentStatusToAssetStatus($equipmentStatus)
    {
        $statusMap = [
            'berfungsi_100' => 'siap_dipakai',
            'berfungsi_sebagian' => 'perlu_perbaikan',
            'menunggu_komponen' => 'dalam_perbaikan',
            'dalam_pemesanan' => 'dalam_perbaikan',
            'perlu_penggantian' => 'perlu_perbaikan',
            'tidak_dapat_diperbaiki' => 'rusak',
            'rusak_total' => 'rusak'
        ];

        return $statusMap[$equipmentStatus] ?? 'dalam_perbaikan';
    }

    public function archive(MaintenanceLog $maintenance)
    {
        $maintenance->status = 'archived';
        $maintenance->save();

        ActivityLogger::log(
            'archive',
            'maintenance',
            'Archived maintenance record: ' . $maintenance->asset->name
        );

        return redirect()->back()
            ->with('success', 'Maintenance berhasil diarsipkan.');
    }

    public function revise(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $maintenance = MaintenanceLog::findOrFail($id);
            $maintenance->status = 'archived'; // Using archived status for revisions
            $maintenance->revision_notes = $request->revision_notes;
            $maintenance->save();
            
            DB::commit();
            
            return redirect()->route('maintenance.approvals')
                ->with('success', 'Maintenance telah dikirim untuk revisi');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error mengirim revisi: ' . $e->getMessage());
        }
    }

    public function restart($id)
    {
        try {
            DB::beginTransaction();
            
            $maintenance = MaintenanceLog::findOrFail($id);
            $maintenance->status = 'in_progress';
            $maintenance->save();
            
            DB::commit();
            
            return redirect('/maintenance?status=active')
                ->with('success', 'Maintenance berhasil dikembalikan ke status Sedang Dikerjakan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}