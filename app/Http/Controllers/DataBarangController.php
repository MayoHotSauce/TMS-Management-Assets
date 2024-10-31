<?php
// app/Http/Controllers/DataBarangController.php

namespace App\Http\Controllers;

use App\Models\DaftarBarang;
use App\Models\Category;
use Illuminate\Http\Request;
use DataTables;

class DataBarangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DaftarBarang::with('category')->select('v_daftar_barang.*');
            return DataTables::of($data)
                ->addColumn('action', function($row){
                    $btn = '<button type="button" data-id="'.$row->id.'" class="edit-btn btn btn-primary btn-sm">Edit</button> ';
                    $btn .= '<button type="button" data-id="'.$row->id.'" class="delete-btn btn btn-danger btn-sm">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $categories = Category::all();
        return view('data-barang.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'room' => 'required|in:Ruang Utama,Ruang Meeting',
            'category_id' => 'required|exists:categories,id',
            'tahun_pengadaan' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        DaftarBarang::create($request->all());
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $barang = DaftarBarang::find($id);
        return response()->json($barang);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required',
            'room' => 'required|in:Ruang Utama,Ruang Meeting',
            'category_id' => 'required|exists:categories,id',
            'tahun_pengadaan' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $barang = DaftarBarang::find($id);
        $barang->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        DaftarBarang::find($id)->delete();
        return response()->json(['success' => true]);
    }
}