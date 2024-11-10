<?php
namespace App\Http\Controllers;

use App\Models\AssetRequest;
use App\Mail\AssetRequestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        Mail::to($validated['approver_email'])
            ->send(new AssetRequestMail($assetRequest));

        return redirect()
            ->route('pengajuan.index')
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

        $assetRequest->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);

        return redirect()
            ->route('pengajuan.show', $id)
            ->with('success', 'Asset request has been approved successfully.');
    }

    public function decline($id, $token)
    {
        $assetRequest = AssetRequest::where('id', $id)
            ->where('approval_token', $token)
            ->firstOrFail();

        $assetRequest->update([
            'status' => 'declined'
        ]);

        return redirect()
            ->route('pengajuan.show', $id)
            ->with('success', 'Asset request has been declined.');
    }
}