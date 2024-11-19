<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
    {
        // Enable query log untuk debugging
        DB::enableQueryLog();
        
        try {
            $barang = Asset::query()
                ->select('id', 'name', 'asset_tag', 'category_id', 'room_id', 
                        'purchase_date', 'purchase_cost', 'status', 'description')
                ->with(['room:id,name', 'category:id,name'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Log query yang dijalankan
            \Log::info('Queries executed:', DB::getQueryLog());
            \Log::info('Total records:', ['count' => $barang->total()]);

            return view('barang.index', compact('barang'));
            
        } catch (\Exception $e) {
            \Log::error('Error in BarangController@index: ' . $e->getMessage());
            return view('barang.index')->with('error', 'Error loading data: ' . $e->getMessage());
        }
    }
}