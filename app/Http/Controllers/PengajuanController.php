<?php

namespace App\Http\Controllers;

use App\Models\AssetRequest;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view pengajuan', ['only' => ['index', 'show']]);
        $this->middleware('permission:create pengajuan', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit pengajuan', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete pengajuan', ['only' => ['destroy']]);
        $this->middleware('permission:approve pengajuan', ['only' => ['approve']]);
        $this->middleware('permission:reject pengajuan', ['only' => ['reject']]);
        $this->middleware('permission:submit proof pengajuan', ['only' => ['submitProofForm', 'submitProof']]);
        $this->middleware('permission:final approve pengajuan', ['only' => ['finalApprove']]);
        $this->middleware('permission:final reject pengajuan', ['only' => ['finalReject']]);
    }

    public function index(Request $request)
    {
        $query = AssetRequest::query()->with(['user', 'room']);

        $status = $request->get('status', 'pending');

        switch ($status) {
            case 'pending':
                $query->where('status', 'pending');
                break;
            case 'active':
                $query->where('status', 'approved');
                break;
            case 'bukti':
                $query->whereIn('status', ['bukti', 'final_approval']);
                break;
            case 'completed':
                $query->whereIn('status', ['completed', 'rejected']);
                break;
        }

        $requests = $query->latest()->paginate(10);
        return view('pengajuan.index', compact('requests', 'status'));
    }

    public function create()
    {
        $categories = Category::pluck('name', 'id');
        $rooms = Room::all();
        
        // Get all users instead of filtering by role
        $users = User::pluck('email', 'id');
        
        return view('pengajuan.create', compact('categories', 'rooms', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000'
        ]);

        $assetRequest = new AssetRequest();
        $assetRequest->name = $validated['name'];
        $assetRequest->category = Category::find($validated['category_id'])->name;
        $assetRequest->room_id = $validated['room_id'];
        $assetRequest->price = $validated['price'];
        $assetRequest->description = $request->description ?: null;
        $assetRequest->user_id = auth()->id();
        $assetRequest->requester_email = auth()->user()->email;
        $assetRequest->status = 'pending';
        $assetRequest->save();

        ActivityLogger::log(
            'create',
            'asset_request',
            'Created new asset request: ' . $assetRequest->name
        );

        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan asset berhasil dibuat.');
    }

    public function approvals()
    {
        $firstApprovals = AssetRequest::where('status', 'pending')
            ->latest()
            ->get();

        $finalApprovals = AssetRequest::whereIn('status', ['bukti', 'final_approval'])
            ->latest()
            ->get();

        return view('pengajuan.approvals', compact('firstApprovals', 'finalApprovals'));
    }

    public function approve(AssetRequest $pengajuan)
    {
        \Log::info('Approve method called for request:', ['id' => $pengajuan->id]);
        
        try {
            DB::beginTransaction();
            
            \Log::info('Current status:', ['status' => $pengajuan->status]);
            
            $pengajuan->status = 'approved';
            $pengajuan->approved_at = now();
            $pengajuan->save();
            
            \Log::info('Status updated to approved');
            
            DB::commit();

            ActivityLogger::log(
                'approve',
                'asset_request',
                'Menyetujui pengajuan asset: ' . $pengajuan->name
            );

            return redirect()->route('pengajuan.approvals')
                ->with('success', 'Pengajuan telah disetujui dan menunggu bukti pembelian.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in approve method:', ['error' => $e->getMessage()]);
            
            return redirect()->route('pengajuan.approvals')
                ->with('error', 'Gagal menyetujui pengajuan: ' . $e->getMessage());
        }
    }

    public function reject(AssetRequest $pengajuan, Request $request)
    {
        try {
            $pengajuan->status = 'declined';
            $pengajuan->notes = $request->notes;
            $pengajuan->save();

            ActivityLogger::log(
                'reject',
                'asset_request',
                'Rejected asset request: ' . $pengajuan->name,
                null,
                ['notes' => $request->notes]
            );

            return redirect()->route('pengajuan.approvals')
                ->with('success', 'Pengajuan berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->route('pengajuan.approvals')
                ->with('error', 'Gagal menolak pengajuan: ' . $e->getMessage());
        }
    }

    public function show(AssetRequest $pengajuan)
    {
        return view('pengajuan.show', compact('pengajuan'));
    }

    public function archive(AssetRequest $pengajuan)
    {
        $pengajuan->status = 'archived';
        $pengajuan->save();

        ActivityLogger::log(
            'archive',
            'asset_request',
            'Archived asset request: ' . $pengajuan->name
        );

        return redirect()->back()
            ->with('success', 'Pengajuan berhasil diarsipkan.');
    }

    public function showSubmitProofForm(AssetRequest $pengajuan)
    {
        if ($pengajuan->status !== 'approved') {
            return redirect()->route('pengajuan.index')
                ->with('error', 'Pengajuan harus berstatus approved untuk submit bukti.');
        }

        return view('pengajuan.submit-proof', compact('pengajuan'));
    }

    public function submitProof(Request $request, AssetRequest $pengajuan)
    {
        $request->validate([
            'proof_image' => 'required|image|max:2048',
            'final_cost' => 'required|numeric|min:0',
            'proof_description' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('proof_image')) {
                $file = $request->file('proof_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/proofs', $filename);
                
                $pengajuan->proof_image = $filename;
            }

            $pengajuan->status = 'final_approval';
            $pengajuan->final_cost = $request->final_cost;
            $pengajuan->proof_description = $request->proof_description;
            $pengajuan->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembelian berhasil disubmit'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in submitProof:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal submit bukti pembelian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function finalApprove(Request $request, AssetRequest $pengajuan)
    {
        try {
            $pengajuan->status = 'completed';
            $pengajuan->save();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan telah disetujui final'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function finalReject(Request $request, AssetRequest $pengajuan)
    {
        try {
            $pengajuan->status = 'rejected';
            $pengajuan->rejection_reason = $request->reason;
            $pengajuan->save();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan telah ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function createAssetFromRequest(AssetRequest $pengajuan)
    {
        $asset = new Asset();
        $asset->name = $pengajuan->name;
        $asset->category_id = Category::where('name', $pengajuan->category)->first()->id;
        $asset->room_id = $pengajuan->room_id;
        $asset->description = $pengajuan->description;
        $asset->purchase_date = now();
        $asset->purchase_cost = $pengajuan->final_cost;
        $asset->status = 'siap_dipakai';

        // Generate unique asset tag
        $nextId = Asset::max('id') + 1;
        $asset->asset_tag = 'TMS-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        
        $asset->save();
        
        return $asset;
    }
} 