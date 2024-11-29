<?php
namespace App\Http\Controllers;

use App\Models\AssetRequest;
use App\Mail\AssetRequestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Services\ActivityLogger;

class AssetRequestController extends Controller
{
    public function index()
    {
        $requests = AssetRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pengajuan.index', compact('requests'));
    }

    public function create()
    {
        $categories = [
            'Electronics' => 'Electronics',
            'Furniture' => 'Furniture',
            'Office Supplies' => 'Office Supplies',
            'Software' => 'Software',
            'Others' => 'Others'
        ];
        
        return view('pengajuan.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable',
            'approver_email' => 'required|email'
        ]);

        $approval_token = Str::random(32);

        $assetRequest = AssetRequest::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'approver_email' => $validated['approver_email'],
            'requester_email' => auth()->user()->email,
            'user_id' => auth()->id(),
            'status' => 'pending',
            'approval_token' => $approval_token
        ]);

        ActivityLogger::log(
            'create',
            'asset_request',
            'Created new asset request: ' . $assetRequest->name,
            null,
            $assetRequest->toArray()
        );

        Mail::to($validated['approver_email'])
            ->send(new AssetRequestMail($assetRequest));

        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan berhasil dikirim dan menunggu persetujuan.');
    }

    public function show($id)
    {
        $assetRequest = AssetRequest::where('user_id', auth()->id())
            ->findOrFail($id);
            
        return view('pengajuan.show', compact('assetRequest'));
    }

    public function approve($id, $token)
    {
        $assetRequest = AssetRequest::where('id', $id)
            ->where('approval_token', $token)
            ->firstOrFail();

        $oldValues = $assetRequest->toArray();
        $assetRequest->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);

        ActivityLogger::log(
            'approve',
            'asset_request',
            'Approved asset request: ' . $assetRequest->name,
            $oldValues,
            $assetRequest->toArray()
        );

        return redirect()->route('pengajuan.show', $id)
            ->with('success', 'Asset request has been approved successfully.');
    }

    public function decline($id, $token)
    {
        $assetRequest = AssetRequest::where('id', $id)
            ->where('approval_token', $token)
            ->firstOrFail();

        $oldValues = $assetRequest->toArray();
        $assetRequest->update([
            'status' => 'declined'
        ]);

        ActivityLogger::log(
            'decline',
            'asset_request',
            'Declined asset request: ' . $assetRequest->name,
            $oldValues,
            $assetRequest->toArray()
        );

        return redirect()->route('pengajuan.show', $id)
            ->with('success', 'Asset request has been declined.');
    }

    public function handleApproval($id, $token)
    {
        $assetRequest = AssetRequest::where('id', $id)
            ->where('approval_token', $token)
            ->first();

        if (!$assetRequest) {
            return view('approval.error');
        }

        // Check if already processed
        if ($assetRequest->status !== 'pending') {
            return view('approval.error')
                ->with('message', 'This request has already been processed.');
        }

        $assetRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approval_token' => null // Invalidate token after use
        ]);

        return view('approval.success')
            ->with('status', 'approved');
    }

    public function handleDecline($id, $token)
    {
        $assetRequest = AssetRequest::where('id', $id)
            ->where('approval_token', $token)
            ->first();

        if (!$assetRequest) {
            return view('approval.error');
        }

        // Check if already processed
        if ($assetRequest->status !== 'pending') {
            return view('approval.error')
                ->with('message', 'This request has already been processed.');
        }

        $assetRequest->update([
            'status' => 'declined',
            'approved_at' => now(),
            'approval_token' => null // Invalidate token after use
        ]);

        return view('approval.success')
            ->with('status', 'declined');
    }

    public function update(Request $request, $id)
    {
        $assetRequest = AssetRequest::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable',
            'approver_email' => 'required|email'
        ]);

        $oldValues = $assetRequest->toArray();
        $assetRequest->update($validated);

        ActivityLogger::log(
            'update',
            'asset_request',
            'Updated asset request: ' . $assetRequest->name,
            $oldValues,
            $assetRequest->toArray()
        );

        return redirect()->route('pengajuan.index')
            ->with('success', 'Asset request updated successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $assetRequest = AssetRequest::findOrFail($id);
        $oldValues = $assetRequest->toArray();

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,declined'
        ]);

        $assetRequest->update([
            'status' => $validated['status'],
            'approved_at' => now()
        ]);

        ActivityLogger::log(
            'update_status',
            'asset_request',
            'Updated asset request status to ' . $validated['status'] . ' for: ' . $assetRequest->name,
            $oldValues,
            $assetRequest->toArray()
        );

        return redirect()->route('pengajuan.index')
            ->with('success', 'Status updated successfully');
    }
}