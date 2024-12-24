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
                $query->where('status', 'bukti');
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
            ->with(['user', 'room'])
            ->latest()
            ->get();

        $finalApprovals = AssetRequest::where('status', 'final_approval')
            ->with(['user', 'room'])
            ->latest()
            ->get();

        return view('pengajuan.approvals', compact('firstApprovals', 'finalApprovals'));
    }

    public function approve(AssetRequest $pengajuan)
    {
        try {
            $pengajuan->status = 'active';
            $pengajuan->approved_at = now();
            $pengajuan->save();

            ActivityLogger::log(
                'approve',
                'asset_request',
                'Menyetujui pengajuan asset: ' . $pengajuan->name
            );

            return redirect()->route('pengajuan.approvals')
                ->with('success', 'Pengajuan telah disetujui dan menunggu bukti pembelian.');
        } catch (\Exception $e) {
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
        $pengajuan = AssetRequest::findOrFail($pengajuan->id);
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

    public function submitProof(Request $request, AssetRequest $pengajuan)
    {
        $validated = $request->validate([
            'proof_image' => 'required|image|max:2048',
            'proof_description' => 'required|string',
            'total_cost' => 'required|numeric'
        ]);

        try {
            DB::transaction(function () use ($pengajuan, $validated, $request) {
                $imagePath = $request->file('proof_image')->store('proofs', 'public');
                
                $pengajuan->proof_image = $imagePath;
                $pengajuan->proof_description = $validated['proof_description'];
                $pengajuan->final_cost = $validated['total_cost'];
                $pengajuan->status = 'final_approval';
                $pengajuan->save();

                ActivityLogger::log(
                    'submit_proof',
                    'asset_request',
                    'Submitted proof for asset request: ' . $pengajuan->name
                );
            });

            return redirect()->route('pengajuan.index', ['status' => 'bukti'])
                ->with('success', 'Bukti berhasil disubmit dan menunggu persetujuan final.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload bukti: ' . $e->getMessage());
        }
    }

    public function showProofForm(AssetRequest $pengajuan)
    {
        if ($pengajuan->status !== 'active') {
            return redirect()->route('pengajuan.index')
                ->with('error', 'Pengajuan harus berstatus aktif untuk submit bukti.');
        }

        return view('pengajuan.submit-proof', compact('pengajuan'));
    }

    public function finalApprove(AssetRequest $pengajuan)
    {
        try {
            DB::transaction(function () use ($pengajuan) {
                $pengajuan->status = 'completed';
                $pengajuan->completed_at = now();
                $pengajuan->save();

                // Create asset record if needed
                $this->createAssetFromRequest($pengajuan);

                ActivityLogger::log(
                    'final_approve',
                    'asset_request',
                    'Menyetujui final pengajuan asset: ' . $pengajuan->name
                );
            });

            return redirect()->route('pengajuan.approvals')
                ->with('success', 'Pengajuan telah disetujui final dan asset telah dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('pengajuan.approvals')
                ->with('error', 'Gagal menyetujui final: ' . $e->getMessage());
        }
    }

    public function finalReject(Request $request, AssetRequest $pengajuan)
    {
        try {
            $pengajuan->status = 'rejected';
            $pengajuan->rejection_notes = $request->notes;
            $pengajuan->rejected_at = now();
            $pengajuan->save();

            ActivityLogger::log(
                'final_reject',
                'asset_request',
                'Menolak final pengajuan asset: ' . $pengajuan->name,
                null,
                ['notes' => $request->notes]
            );

            return redirect()->route('pengajuan.approvals')
                ->with('success', 'Pengajuan telah ditolak pada tahap final.');
        } catch (\Exception $e) {
            return redirect()->route('pengajuan.approvals')
                ->with('error', 'Gagal menolak pengajuan: ' . $e->getMessage());
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