<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\DeductionType;
use Illuminate\Http\Request;

class DeductionTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DeductionType::select(['id', 'name', 'description', 'status']);
            return datatables()->of($data)
                ->addColumn('status', function($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('action', function($row) {
                    return '<div class="text-center">
                        <button class="btn btn-sm btn-outline-info edit-btn" data-id="' . $row->id . '">
                            <i class="ri-edit-line"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $row->id . '">
                            <i class="ri-delete-bin-line"></i> Delete
                        </button>
                        </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('settings.deduction_types');
    }

    public function create()
    {
        return view('settings.partials.deduction_type_form', ['deductionType' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:deduction_types,name',
            'description' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);
        DeductionType::create($validated);
        return response()->json(['success' => true]);
    }

    public function show(DeductionType $deductionType)
    {
        return view('settings.partials.deduction_type_form', ['deductionType' => $deductionType]);
    }

    public function edit(DeductionType $deductionType)
    {
        return view('settings.partials.deduction_type_form', ['deductionType' => $deductionType]);
    }

    public function update(Request $request, DeductionType $deductionType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:deduction_types,name,' . $deductionType->id,
            'description' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);
        $deductionType->update($validated);
        return response()->json(['success' => true, 'data' => $deductionType]);
    }

    public function destroy(DeductionType $deductionType)
    {
        $deductionType->delete();
        return response()->json(['success' => true]);
    }
}
