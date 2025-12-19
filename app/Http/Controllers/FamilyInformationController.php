<?php

namespace App\Http\Controllers;

use App\Models\FamilyInformation;
use App\Models\SiblingInformation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FamilyInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $family = FamilyInformation::whereHas('children', function ($query) use ($request) {
                $query->where('student_id', $request->student);
            })->with('parent')->first();

            if (isset($family)) {
                $data = SiblingInformation::where('family_information_id', $family->id)->with('student')->orderBy("updated_at")->get();
            } else {
                $data = [];
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    return view('students.student_image_tr', ['row' => $row->student]);
                })
                ->addColumn('sibling_no', function ($row) {
                    return str_ordinal($row->sibling_no);
                })
                ->rawColumns(['employee_no', 'action'])
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FamilyInformation  $familyInformation
     * @return \Illuminate\Http\Response
     */
    public function show(FamilyInformation $familyInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FamilyInformation  $familyInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(FamilyInformation $familyInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FamilyInformation  $familyInformation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FamilyInformation $familyInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FamilyInformation  $familyInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(FamilyInformation $familyInformation)
    {
        //
    }
}
