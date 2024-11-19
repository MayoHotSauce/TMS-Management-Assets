<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAssets = Asset::count();
        $needMaintenance = Asset::where('status', 'need_maintenance')->count();
        $inMaintenance = Asset::where('status', 'in_maintenance')->count();
        $totalMaintenance = Maintenance::count();

        // Query untuk 5 barang terbaru
        $recentDaftarBarang = Asset::select('name', 'description')
            ->orderBy('created_at', 'desc')
            ->take(5)  // Mengambil 5 data terbaru
            ->get();

        // Query untuk 5 maintenance terbaru
        $recentMaintenance = Maintenance::with(['asset:id,name'])
            ->select('id', 'barang_id', 'description')
            ->orderBy('created_at', 'desc')
            ->take(5)  // Mengambil 5 data terbaru
            ->get();

        // Query untuk rooms
        $rooms = Room::withCount('assets')
            ->orderBy('name')
            ->get();

        return view('dashboard.index', compact(
            'totalAssets',
            'needMaintenance',
            'inMaintenance',
            'totalMaintenance',
            'recentDaftarBarang',
            'recentMaintenance',
            'rooms'
        ));
    }
}
