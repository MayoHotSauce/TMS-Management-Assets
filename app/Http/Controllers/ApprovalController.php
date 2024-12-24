<?php

namespace App\Http\Controllers;

use App\Models\AssetRequest;
use Illuminate\Http\Request;

class ApprovalController
{
    public function index()
    {
        $pendingRequests = AssetRequest::where('status', 'pending')
            ->latest()
            ->get();

        $finalApprovalRequests = AssetRequest::where('status', 'final_approval')
            ->latest()
            ->get();

        return view('pengajuan.approvals', compact('pendingRequests', 'finalApprovalRequests'));
    }
} 