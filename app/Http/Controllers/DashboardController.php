<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarBarang;
use App\Models\AssetTransfer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAssets = 0;
        $assetsNeedingMaintenance = 0;
        $assetsInMaintenance = 0;
        $assetsByRoom = collect();
        $assetsByCategory = collect();
        $maintenanceDue = collect();
        $pendingTransfers = collect();

        return view('dashboard', compact(
            'totalAssets',
            'assetsNeedingMaintenance',
            'assetsInMaintenance',
            'assetsByRoom',
            'assetsByCategory',
            'maintenanceDue',
            'pendingTransfers'
        ));
    }
}
