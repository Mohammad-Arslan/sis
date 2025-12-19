<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ProvidentFundDefinition;
use Illuminate\Http\Request;

class ProvidentFundDefinitionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProvidentFundDefinition::select(['id', 'fiscal_year', 'employee_contribution_percent', 'employer_contribution_percent', 'status']);
            return datatables()->of($data)
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-outline-info edit-btn" data-id="' . $row->id . '"><i class="ri-edit-line"></i> Edit</button>
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $row->id . '"><i class="ri-delete-bin-line"></i> Delete</button>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('settings.provident_fund_definitions');
    }

    public function create()
    {
        return view('settings.partials.provident_fund_definition_form', ['definition' => null]);
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
                    // Check if fiscal year format is correct (e.g., 2024-2025)
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
                'unique:provident_fund_definitions,fiscal_year'
            ],
            'employee_contribution_percent' => 'required|numeric|min:0|max:100',
            'employer_contribution_percent' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean',
        ]);

        // If creating an active definition, deactivate all previous ones
        if ($validated['status'] == 1) {
            ProvidentFundDefinition::where('status', 1)->update(['status' => 0]);
        }

        $definition = ProvidentFundDefinition::create($validated);
        return response()->json(['success' => true, 'data' => $definition]);
    }

    public function show(ProvidentFundDefinition $providentFundDefinition)
    {
        //
    }

    public function edit(ProvidentFundDefinition $providentFundDefinition)
    {
        return view('settings.partials.provident_fund_definition_form', ['definition' => $providentFundDefinition]);
    }

    public function update(Request $request, ProvidentFundDefinition $providentFundDefinition)
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
                'unique:provident_fund_definitions,fiscal_year,' . $providentFundDefinition->id
            ],
            'employee_contribution_percent' => 'required|numeric|min:0|max:100',
            'employer_contribution_percent' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean',
        ]);

        $providentFundDefinition->update($validated);
        return response()->json(['success' => true, 'data' => $providentFundDefinition]);
    }

    public function destroy(ProvidentFundDefinition $providentFundDefinition)
    {
        $providentFundDefinition->delete();
        return response()->json(['success' => true]);
    }
}
