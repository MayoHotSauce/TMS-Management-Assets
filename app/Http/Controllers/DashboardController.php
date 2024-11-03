<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch total assets
        $totalAssets = Asset::count();

        // Fetch total maintenance logs
        $totalMaintenance = Maintenance::count();

        // Fetch maintenance logs with scheduled status
        $needMaintenance = Maintenance::where('status', 'scheduled')->count();

        // Fetch maintenance logs with pending status
        $inMaintenance = Maintenance::where('status', 'pending')->count();

        // Fetch maintenance logs that are due soon
        $maintenances = Maintenance::where('due_date', '<=', now()->addDays(7))->get();

        // Fetch recent assets
        $recentDaftarBarang = DB::table('assets')
            ->select('id', 'description')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Fetch recent maintenance logs
        $recentMaintenance = DB::table('maintenance_logs')
            ->select('barang_id', 'description')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('totalAssets', 'totalMaintenance', 'needMaintenance', 'inMaintenance', 'maintenances', 'recentDaftarBarang', 'recentMaintenance'));
    }
}
