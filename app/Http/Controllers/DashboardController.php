<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MaintenanceLog;
use App\Models\AssetTransfer;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Asset Statistics
        $totalAssets = Barang::count();
        $assetsNeedingMaintenance = Barang::where('condition', 'needs_maintenance')->count();
        $assetsInMaintenance = Barang::where('status', 'maintenance')->count();
        
        // Asset Distribution by Room
        $assetsByRoom = Barang::select('room', DB::raw('count(*) as total'))
            ->groupBy('room')
            ->get();
            
        // Asset Distribution by Category
        $assetsByCategory = Barang::select('categories.name', DB::raw('count(*) as total'))
            ->join('categories', 'categories.id', '=', 'daftar_barang.category_id')
            ->groupBy('categories.name')
            ->get();
            
        // Recent Maintenance
        $recentMaintenance = MaintenanceLog::with('barang')
            ->orderBy('maintenance_date', 'desc')
            ->limit(5)
            ->get();
            
        // Pending Transfers
        $pendingTransfers = AssetTransfer::with('barang')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Assets Requiring Maintenance Soon
        $maintenanceDue = Barang::whereNotNull('next_maintenance')
            ->where('next_maintenance', '<=', now()->addDays(30))
            ->orderBy('next_maintenance')
            ->limit(5)
            ->get();
            
        return view('dashboard', compact(
            'totalAssets',
            'assetsNeedingMaintenance',
            'assetsInMaintenance',
            'assetsByRoom',
            'assetsByCategory',
            'recentMaintenance',
            'pendingTransfers',
            'maintenanceDue'
        ));
    }
}