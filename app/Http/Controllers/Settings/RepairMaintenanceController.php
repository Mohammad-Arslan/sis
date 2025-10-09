<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\RepairMaintenance;
use App\Models\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RepairMaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = RepairMaintenance::with('branch')->get();
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
        return view('settings.repair-maintenance.index', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'nature_of_job' => 'required|string|max:255',
            'locations' => 'required|string|max:255',
            'name_of_reported_dep' => 'required|string|max:255',
            'remarks' => 'required|string|max:500',
        ]);

        RepairMaintenance::create($request->all());

        return response()->json(['success' => 'Repair & Maintenance record created successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $repairMaintenance = RepairMaintenance::find($id);
        return response()->json($repairMaintenance);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'nature_of_job' => 'required|string|max:255',
            'locations' => 'required|string|max:255',
            'name_of_reported_dep' => 'required|string|max:255',
            'remarks' => 'required|string|max:500',
        ]);

        $repairMaintenance = RepairMaintenance::find($id);
        $repairMaintenance->update($request->all());

        return response()->json(['success' => 'Repair & Maintenance record updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        RepairMaintenance::find($id)->delete();
        return response()->json(['success' => 'Repair & Maintenance record deleted successfully.']);
    }
}
