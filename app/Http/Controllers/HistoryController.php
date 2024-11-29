<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by module if specified
        if ($request->has('module') && $request->module !== '') {
            $query->where('module', $request->module);
        }

        // Filter by action if specified
        if ($request->has('action') && $request->action !== '') {
            $query->where('action', $request->action);
        }

        // Remove view actions from results
        $query->where('action', '!=', 'view');

        $activities = $query->paginate(10);
        
        return view('history.index', compact('activities'));
    }
} 