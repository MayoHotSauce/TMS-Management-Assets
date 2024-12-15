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
        $query = AssetRequest::query()->with('user');

        // Add filter functionality
        $status = $request->get('status', 'active'); // Default to active

        switch ($status) {
            case 'active':
                $query->whereIn('status', ['pending']);
                break;
            case 'completed':
                $query->whereIn('status', ['approved', 'declined']);
                break;
            case 'archived':
                $query->whereIn('status', ['archived']);
                break;
            default:
                $query->whereIn('status', ['pending']);
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
        try {
            $requests = AssetRequest::query()
                ->where('status', '=', 'pending')
                ->with('user')
                ->latest()
                ->paginate(10);

            \Log::info('SQL Query: ' . AssetRequest::where('status', 'pending')->toSql());
            \Log::info('Request Count: ' . $requests->count());

            return view('pengajuan.approvals', compact('requests'));
        } catch (\Exception $e) {
            \Log::error('Approval Error: ' . $e->getMessage());
            return back()->with('error', 'Error loading approvals: ' . $e->getMessage());
        }
    }

    public function approve(AssetRequest $pengajuan)
    {
        try {
            DB::transaction(function () use ($pengajuan) {
                // Update request status
                $pengajuan->status = 'approved';
                $pengajuan->approved_at = now();
                $pengajuan->save();

                // Create new asset
                $asset = new Asset();
                $asset->name = $pengajuan->name;
                $asset->category_id = Category::where('name', $pengajuan->category)->first()->id;
                $asset->room_id = $pengajuan->room_id;
                $asset->description = $pengajuan->description;
                $asset->purchase_date = now();
                $asset->purchase_cost = $pengajuan->price;
                $asset->status = 'siap_dipakai';

                // Generate unique asset tag
                $nextId = 1;
                do {
                    $assetTag = 'TMS-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
                    $exists = Asset::where('asset_tag', $assetTag)->exists();
                    $nextId++;
                } while ($exists);

                $asset->asset_tag = $assetTag;
                $asset->save();

                ActivityLogger::log(
                    'approve',
                    'asset_request',
                    'Approved asset request and created asset: ' . $pengajuan->name,
                    null,
                    ['asset_id' => $asset->id]
                );
            });

            return redirect()->route('pengajuan.approvals')
                ->with('success', 'Pengajuan berhasil disetujui dan asset baru telah dibuat.');
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
} 