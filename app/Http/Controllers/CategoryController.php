<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($request->all());
        
        ActivityLogger::log(
            'create',
            'category',
            'Created new category: ' . $category->name,
            null,
            $category->toArray()
        );

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $oldValues = $category->toArray();
        $category->update($request->all());

        $changes = [];
        if ($oldValues['name'] !== $category->name) {
            $changes[] = "name from '{$oldValues['name']}' to '{$category->name}'";
        }
        if ($oldValues['description'] !== $category->description) {
            $changes[] = "description from '{$oldValues['description']}' to '{$category->description}'";
        }

        ActivityLogger::log(
            'update',
            'category',
            'Updated category: ' . implode(', ', $changes),
            $oldValues,
            $category->toArray()
        );

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $categoryName = $category->name;
        $oldValues = $category->toArray();
        
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}