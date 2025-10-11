<?php

namespace App\Http\Controllers;

use App\Models\PTM;
use Illuminate\Http\Request;

class PTMController extends Controller
{
    public function index()
    {
        return view('students.ptm.ptm_info');
    }

    public function getPTMInfo($student_id)
    {
        if (request()->ajax()) {
            $ptm_info = PTM::where('student_id', $student_id)
                ->select(['id', 'ptm_date', 'ptm_type', 'discussion_summary', 'outcomes', 'notes', 'created_at'])
                ->latest();
            return datatables()->of($ptm_info)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="d-flex gap-2">
                        <a href="javascript:void(0)" class="edit btn btn-primary btn-sm" data-id="' . $row->id . '">Edit</a>
                        <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="' . $row->id . '">Delete</a>
                    </div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('students.ptm.ptm_info_form');
    }
    public function edit($id)
    {
        $ptm = PTM::findOrFail($id);
        $ptm->ptm_date = \Carbon\Carbon::parse($ptm->ptm_date)->format('d-m-Y');
        return response()->json($ptm);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ptm_date' => 'required|date',
            'ptm_type' => 'required|string',
            'discussion_summary' => 'required|string',
            'outcomes' => 'required|string',
            'notes' => 'required|string',
        ]);
        $validated['student_id'] = $id;
        $validated['ptm_date'] = date('Y-m-d', strtotime($validated['ptm_date']));

        // Check if there's already a record for this date
        $existingPTM = PTM::where('student_id', $id)
            ->where('ptm_date', $validated['ptm_date'])
            ->where('id', '!=', $request->id) // Exclude current record when updating
            ->first();

        if ($existingPTM) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['ptm_date' => 'A PTM record already exists for this date.']);
        }

        PTM::updateOrCreate(['id' => $request->id], $validated);
        return redirect()->back()->with('success', 'PTM info updated successfully');
    }

    public function destroy($id)
    {
        PTM::find($id)->delete();
        return response()->json(['message' => 'PTM info deleted successfully']);
    }
}
