<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;

class BarangController extends Controller
{
    public function index()
    {
        DB::enableQueryLog();
        
        try {
            $barang = Asset::query()
                ->select('id', 'name', 'asset_tag', 'category_id', 'room_id', 
                        'purchase_date', 'purchase_cost', 'status', 'description')
                ->with(['room:id,name', 'category:id,name'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('barang.index', compact('barang'));
            
        } catch (\Exception $e) {
            return view('barang.index')->with('error', 'Error loading data: ' . $e->getMessage());
        }
    }
}