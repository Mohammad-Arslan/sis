<?php

namespace App\Http\Controllers;

use App\Models\GradeBookHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class GradeBookHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        $data = [];
        if($request->student_id !== 0){
            $data = GradeBookHistory::with([
                'student' => function($q) { $q->select('id') ->addSelect(DB::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name) AS full_name"));},
                'student_behaviour_skill.academic_year' => function($q){ $q->select('id', 'title');},
                'student_behaviour_skill.branch' => function($q){ $q->select('id','br_name');},
                'student_behaviour_skill.com_class' => function($q){ $q->select('id','class_name');},
                'student_behaviour_skill.section' => function($q) { $q->select('id', 'section_name');},
                'student_behaviour_skill.term' => function($q) { $q->select('id', 'name');},
            ])->where('student_id','=', $request->student_id)->get();
        }

        if ($request->ajax()) {

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('gradebook_history.action',['data'=> $data]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('gradebook_history.index', $data);
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
     * @param  \App\Models\GradeBookHistory  $gradeBookHistory
     * @return \Illuminate\Http\Response
     */
    public function show(GradeBookHistory $gradeBookHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GradeBookHistory  $gradeBookHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(GradeBookHistory $gradeBookHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GradeBookHistory  $gradeBookHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GradeBookHistory $gradeBookHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GradeBookHistory  $gradeBookHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(GradeBookHistory $gradeBookHistory)
    {
        //
    }
}
