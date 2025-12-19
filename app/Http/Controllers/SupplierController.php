<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\AssetCategory;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('fixed-assets.suppliers.index', compact('suppliers'));
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:suppliers,code',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:suppliers,email',
            'phone' => 'required|string|max:50|unique:suppliers,phone',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:100',
            'bank_details' => 'nullable|string',
            'compliance_certificates' => 'nullable|string',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:asset_categories,id',
            'is_active' => 'required|in:true,false'
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'code' => $request->code,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'tax_id' => $request->tax_id,
            'bank_details' => $request->bank_details,
            'compliance_certificates' => $request->compliance_certificates,
            'category_ids' => $request->category_ids ?? [],
            'is_active' => $request->is_active === 'true' || $request->is_active === '1'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier created successfully',
            'data' => $supplier
        ], 201);
    }

    /**
     * Get all suppliers for dropdown/select options.
     */
    public function getSuppliers()
    {
        $suppliers = Supplier::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json($suppliers);
    }

    /**
     * Generate a random code for suppliers.
     */
    public function generateCode()
    {
        $code = Supplier::generateCode();

        return response()->json([
            'code' => $code
        ]);
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:suppliers,code,' . $id,
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:suppliers,email,' . $id,
            'phone' => 'required|string|max:50|unique:suppliers,phone,' . $id,
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:100',
            'bank_details' => 'nullable|string',
            'compliance_certificates' => 'nullable|string',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:asset_categories,id',
            'is_active' => 'required|in:true,false,1,0'
        ]);

        $supplier->update([
            'name' => $request->name,
            'code' => $request->code,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'tax_id' => $request->tax_id,
            'bank_details' => $request->bank_details,
            'compliance_certificates' => $request->compliance_certificates,
            'category_ids' => $request->category_ids ?? [],
            'is_active' => $request->is_active == 'true' || $request->is_active == '1'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully',
            'data' => $supplier
        ]);
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully'
        ]);
    }

    /**
     * Get asset categories for dropdown selection
     */
    public function getCategories()
    {
        $categories = AssetCategory::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json($categories);
    }
}
