<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchClass;
use App\Models\BranchClassSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class BranchClassSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = BranchClassSection::with(['sections', 'com_classes', 'branches']);
            if (isset($request->branch_id)) {
                $data = $data->where('branch_id', $request->branch_id);
            } else if (! Auth::user()->hasRole('super_admin') && ! auth()->user()->hasPermission('approve-lesson-plan')) {
                $data = $data->where('branch_id', get_branch_id());
            }
            $data = $data->get();
            // dd($data->toArray());

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($request) {
                    return view('branches.branch_class_section_actions', ['row' => $row,'request' => $request]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required'
        ]);

        $class = BranchClass::where([
            'branch_id' => $request->branch_id,
            'class_id' => $request->class_id
        ])->exists();

        if (! $class) {
            BranchClass::create($request->all());
        }

        $section = BranchClassSection::where([
            'branch_id' => $request->branch_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
        ])->exists();

        if ($section) {
            return redirect()->back(302)->with('error', 'Section already exists.');
        }

        BranchClassSection::create($request->all());
        return redirect(route('branches.edit', $request->branch_id) . '?tab=branch-classes')->with('success', 'Class ceated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $branchClassSection = BranchClassSection::findOrFail($id);

            // Check if there are any related records that would prevent deletion
            if ($branchClassSection->class_students()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete this class-section relationship as it has associated students. Please remove all students from this class-section first.'
                ], 422);
            }

            if ($branchClassSection->class_teachers()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete this class-section relationship as it has associated teachers. Please remove all teachers from this class-section first.'
                ], 422);
            }

            $branchClassSection->delete();

            return response()->json([
                'success' => true,
                'message' => 'Class-section relationship has been successfully deleted.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the class-section relationship.'
            ], 500);
        }
    }

    /**
     * Get Branch Class Sections.
     */
    public function branch_class_sections($user_type = null)
    {
        /*$data['branchClassSections'] = BranchClassSection::with([
            'branches',
            'sections',
            'com_classes',
        ])->get();*/

        $data['user_type'] = $user_type;

        return view('branches.classes.class_sections', ['data' => $data]);
    }
}
