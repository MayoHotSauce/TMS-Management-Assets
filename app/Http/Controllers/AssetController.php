<?php
namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::with(['category', 'location'])->paginate(10);
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('assets.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_tag' => 'required|string|unique:assets',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric',
            'status' => 'required|in:available,in_use,maintenance,retired'
        ]);

        Asset::create($validated);
        return redirect()->route('assets.index')->with('success', 'Asset created successfully');
    }
}
