<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\PettyCashDetail;
use App\Models\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PettyCashDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PettyCashDetail::with('branch')->get();
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
        return view('settings.petty-cash-details.index', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'opening_balance' => 'required|numeric|min:0',
            'amount_received' => 'required|numeric|min:0',
            'expense' => 'required|numeric|min:0',
            'closing_balance' => 'required|numeric|min:0',
            'details_purpose' => 'required|string|max:500',
        ]);

        PettyCashDetail::create($request->all());

        return response()->json(['success' => 'Petty Cash Detail created successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pettyCashDetail = PettyCashDetail::find($id);
        return response()->json($pettyCashDetail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'opening_balance' => 'required|numeric|min:0',
            'amount_received' => 'required|numeric|min:0',
            'expense' => 'required|numeric|min:0',
            'closing_balance' => 'required|numeric|min:0',
            'details_purpose' => 'required|string|max:500',
        ]);

        $pettyCashDetail = PettyCashDetail::find($id);
        $pettyCashDetail->update($request->all());

        return response()->json(['success' => 'Petty Cash Detail updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        PettyCashDetail::find($id)->delete();
        return response()->json(['success' => 'Petty Cash Detail deleted successfully.']);
    }
}
