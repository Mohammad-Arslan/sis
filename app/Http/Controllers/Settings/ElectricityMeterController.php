<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ElectricityMeterReading;
use App\Models\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ElectricityMeterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ElectricityMeterReading::with('branch')->get();
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
        return view('settings.electricity-meter.index', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'opening_day_reading' => 'required|string|max:50',
            'closing_day_reading' => 'nullable|string|max:50',
            'remark' => 'nullable|string|max:500',
        ]);

        ElectricityMeterReading::create($request->all());

        return response()->json(['success' => 'Electricity Meter Reading created successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $electricityMeterReading = ElectricityMeterReading::find($id);
        return response()->json($electricityMeterReading);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'opening_day_reading' => 'required|string|max:50',
            'closing_day_reading' => 'nullable|string|max:50',
            'remark' => 'nullable|string|max:500',
        ]);

        $electricityMeterReading = ElectricityMeterReading::find($id);
        $electricityMeterReading->update($request->all());

        return response()->json(['success' => 'Electricity Meter Reading updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ElectricityMeterReading::find($id)->delete();
        return response()->json(['success' => 'Electricity Meter Reading deleted successfully.']);
    }
}
