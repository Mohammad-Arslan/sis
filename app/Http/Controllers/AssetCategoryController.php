<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetCategory;

class AssetCategoryController extends Controller
{
    /**
     * Display a listing of asset categories.
     */
    public function index()
    {
        $categories = AssetCategory::with('parent')
            ->orderBy('name')
            ->get();

        return view('fixed-assets.master-data.categories', compact('categories'));
    }

    /**
     * Store a newly created asset category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:asset_categories,code',
            'parent_id' => 'nullable|exists:asset_categories,id',
            'description' => 'nullable|string',
            'is_active' => 'required|in:true,false'
        ]);

        $category = AssetCategory::create([
            'name' => $request->name,
            'code' => $request->code,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Asset category created successfully',
            'data' => $category
        ], 201);
    }

    /**
     * Get all categories for dropdown/select options.
     */
    public function getCategories()
    {
        $categories = AssetCategory::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json($categories);
    }
    
    /**
     * Generate a random code for asset categories.
     */
    public function generateCode()
    {
        // Always use ACAT as the prefix
        $prefix = 'ACAT';
        
        // Generate a random 5-digit number
        $code = $prefix . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        
        return response()->json([
            'code' => $code
        ]);
    }
    
    /**
     * Update the specified asset category.
     */
    public function update(Request $request, $id)
    {
        $category = AssetCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:asset_categories,code,' . $id,
            'parent_id' => 'nullable|exists:asset_categories,id',
            'description' => 'nullable|string',
            'is_active' => 'required|in:true,false,1,0'
        ]);
        
        // Prevent circular reference
        if ($request->parent_id == $id) {
            return response()->json([
                'success' => false,
                'message' => 'A category cannot be its own parent',
                'errors' => ['parent_id' => ['A category cannot be its own parent']]
            ], 422);
        }
        
        $category->update([
            'name' => $request->name,
            'code' => $request->code,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'is_active' => $request->is_active == 'true' || $request->is_active == '1'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Asset category updated successfully',
            'data' => $category
        ]);
    }
    
    /**
     * Remove the specified asset category.
     */
    public function destroy($id)
    {
        $category = AssetCategory::findOrFail($id);
        
        // Update all child categories to have no parent
        if ($category->children()->count() > 0) {
            $category->children()->update(['parent_id' => null]);
        }
        
        $category->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Asset category deleted successfully'
        ]);
    }
}
