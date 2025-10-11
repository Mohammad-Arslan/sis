<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\IncomeTaxSlab;
use Illuminate\Http\Request;

class IncomeTaxSlabController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = IncomeTaxSlab::select(['id', 'fiscal_year', 'min_salary', 'max_salary', 'tax_percent', 'fixed_amount', 'status']);
            return datatables()->of($data)
                ->addColumn('status', function($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('action', function($row) {
                    return '<button class="btn btn-sm btn-outline-info edit-btn" data-id="'.$row->id.'"><i class="ri-edit-line"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="'.$row->id.'"><i class="ri-delete-bin-line"></i> Delete</button>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('settings.income_tax_slabs');
    }

    public function create()
    {
        return view('settings.partials.income_tax_slabs_forms', ['slab' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fiscal_year' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{4}-\d{4}$/',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^(\d{4})-(\d{4})$/', $value, $matches)) {
                        $startYear = (int)$matches[1];
                        $endYear = (int)$matches[2];
                        $currentYear = (int)date('Y');
                        
                        // Check if end year is exactly start year + 1
                        if ($endYear !== $startYear + 1) {
                            $fail('The fiscal year must be in consecutive year format (e.g., 2024-2025).');
                        }
                        
                        // Check if fiscal year is not greater than current year
                        if ($startYear > $currentYear) {
                            $fail('The fiscal year cannot be greater than the current year.');
                        }
                    }
                }
            ],
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'required|numeric|min:0',
            'tax_percent' => 'required|numeric|min:0|max:100',
            'fixed_amount' => 'nullable|numeric|min:0',
            'status' => 'required|boolean',
        ]);
        $slab = IncomeTaxSlab::create($validated);
        return response()->json(['success' => true, 'data' => $slab]);
    }

    public function show(IncomeTaxSlab $incomeTaxSlab)
    {
        return view('settings.partials.income_tax_slabs_forms', ['slab' => $incomeTaxSlab]);
    }

    public function edit(IncomeTaxSlab $incomeTaxSlab)
    {
        return view('settings.partials.income_tax_slabs_forms', ['slab' => $incomeTaxSlab]);
    }

    public function update(Request $request, IncomeTaxSlab $incomeTaxSlab)
    {
        $validated = $request->validate([
            'fiscal_year' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{4}-\d{4}$/',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^(\d{4})-(\d{4})$/', $value, $matches)) {
                        $startYear = (int)$matches[1];
                        $endYear = (int)$matches[2];
                        $currentYear = (int)date('Y');
                        
                        // Check if end year is exactly start year + 1
                        if ($endYear !== $startYear + 1) {
                            $fail('The fiscal year must be in consecutive year format (e.g., 2024-2025).');
                        }
                        
                        // Check if fiscal year is not greater than current year
                        if ($startYear > $currentYear) {
                            $fail('The fiscal year cannot be greater than the current year.');
                        }
                    }
                },
                'unique:income_tax_slabs,fiscal_year,' . $incomeTaxSlab->id
            ],
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'required|numeric|min:0',
            'tax_percent' => 'required|numeric|min:0|max:100',
            'fixed_amount' => 'nullable|numeric|min:0',
            'status' => 'required|boolean',
        ]);
        $incomeTaxSlab->update($validated);
        return response()->json(['success' => true, 'data' => $incomeTaxSlab]);
    }

    public function destroy(IncomeTaxSlab $incomeTaxSlab)
    {
        $incomeTaxSlab->delete();
        return response()->json(['success' => true]);
    }
}
