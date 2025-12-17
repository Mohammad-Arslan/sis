<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\GeneratorInfo;
use App\Models\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GeneratorInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = GeneratorInfo::with('branch')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return $row->branch ? $row->branch->br_name : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary btn-sm">Edit</a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $branches = Branch::all();
        return view('settings.generator-info.index', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'date_refueling' => 'required|date',
            'quantity_liter' => 'required|numeric|min:0',
            'verified_by' => 'required|string|max:255',
            'generator_capacity' => 'required|string|max:100',
            'starting_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'reading' => 'nullable|string|max:50',
        ]);

        GeneratorInfo::create($request->all());

        return response()->json(['success' => 'Generator Information created successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $generatorInfo = GeneratorInfo::find($id);
        return response()->json($generatorInfo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'date_refueling' => 'required|date',
            'quantity_liter' => 'required|numeric|min:0',
            'verified_by' => 'required|string|max:255',
            'generator_capacity' => 'required|string|max:100',
            'starting_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'reading' => 'nullable|string|max:50',
        ]);

        $generatorInfo = GeneratorInfo::find($id);
        $generatorInfo->update($request->all());

        return response()->json(['success' => 'Generator Information updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        GeneratorInfo::find($id)->delete();
        return response()->json(['success' => 'Generator Information deleted successfully.']);
    }
}
