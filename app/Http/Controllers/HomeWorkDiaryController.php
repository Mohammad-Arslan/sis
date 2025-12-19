<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\State;
use App\Models\Branch;
use App\Models\Section;
use App\Models\ComClass;
use App\Models\BranchClass;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\HomeWorkDiary;
use Illuminate\Database\QueryException;

class HomeWorkDiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (isHeadOfficeEmp() || isSuperAdmin()) {
                $data = HomeWorkDiary::with('state', 'branch', 'com_class', 'section', 'academic_year', 'createdBy', 'updatedBy');
            } else {
                $data = HomeWorkDiary::where('branch_id', get_branch_id())->with('state', 'branch', 'com_class', 'section', 'academic_year', 'createdBy', 'updatedBy');
            }
            //dd($data->get()->toArray());

            if ($request->academic_year_id && $request->academic_year_id > 0) {
                $data = $data->where('academic_year_id', $request->academic_year_id);
            }
            if ($request->state_id && $request->state_id > 0) {
                $data = $data->where('state_id', $request->state_id);
            }
            if ($request->branch_id && $request->branch_id > 0) {
                $data = $data->where('branch_id', $request->branch_id);
            }
            if ($request->class_id && $request->class_id > 0) {
                $data = $data->where('class_id', $request->class_id);
            }
            if ($request->section_id && $request->section_id > 0) {
                $data = $data->where('section_id', $request->section_id);
            }
            if ($request->homework_date && $request->homework_date != '') {
                $data = $data->where('homework_date', $request->homework_date);
            }

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('ay', function ($row) {
                return $row->academic_year->title;
            })
                ->addColumn('province', function ($row) {
                    return $row->state->state_name;
                })
                ->addColumn('branch_name', function ($row) {
                    return $row->branch->br_name . '[' . $row->branch->branch_code . ']';
                })
                ->addColumn('class', function ($row) {
                    return $row->com_class->class_name;
                })
                ->addColumn('section', function ($row) {
                    return $row->section ? $row->section->section_name : 'All sections';
                })
                ->addColumn('date', function ($row) {
                    return  Carbon::parse($row->homework_date)->format('d-m-Y');
                })
                ->addColumn('remarks', function ($row) {
                    return  $row->remarks;
                })
                ->addColumn('createdby', function ($row) {
                    $fullName = $row->createdBy->first_name . ' ' . $row->createdBy->last_name;
                    return $fullName;
                })
                ->addColumn('created_on', function ($row) {
                    return  Carbon::parse($row->created_at)->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    return view('homeworkdiary.listing_actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        if (isHeadOfficeEmp() || isSuperAdmin()) {
            $branches = Branch::all();
            $states = State::all();
            $comclasses = ComClass::all();
            $sections = Section::all();
            $academic_years = AcademicYear::all();
        } else {
            $branches = Branch::where('branch_id', get_branch_id());
            $states = State::all();
            $classdata = BranchClass::where('branch_id', get_branch_id())->with(['com_classes'])->get();
            $i = 0;
            foreach ($classdata as $class) {
                $comclasses[$i]['id'] = $class->class_id;
                $comclasses[$i]['class_name'] = $class->com_classes->class_name;
                $i++;
            }
            $sections = Section::all();
            $academic_years = AcademicYear::all();
        }
// dd($states);
        return view('homeworkdiary.index', compact(['academic_years', 'states', 'branches', 'comclasses', 'sections']));
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
        //dd($request->all());
        $request->validate([
            'state_id' => 'required',
            'branch_id' => 'required',
            'class_id' => 'required',
            'academic_year_id' => 'required',
            'homework_date' => 'required',
            //'remarks' => 'required',
            'created_by' => 'required',
        ]);
        if (isset($request->section_id) && $request->section_id > 0) {
            $homework = HomeWorkDiary::where('state_id', $request->state_id)->where('branch_id', $request->branch_id)->where('class_id', $request->class_id)->where('academic_year_id', $request->academic_year_id)->where('section_id', $request->section_id)->where('homework_date', $request->homework_date)->get();
        } else {
            $homework = HomeWorkDiary::where('state_id', $request->state_id)->where('branch_id', $request->branch_id)->where('class_id', $request->class_id)->where('academic_year_id', $request->academic_year_id)->where('homework_date', $request->homework_date)->get();
        }
        if (isset($homework[0])) {
            return redirect()->route('homeWorkDiary.index')->with('error', 'Duplicate entries not allowed.');
        }
        $input = $request->all();
        //dd($input);
        HomeWorkDiary::create($input);
        return redirect()->route('homeWorkDiary.index')->with('success', 'Record has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HomeWorkDiary  $homeWorkDiary
     * @return \Illuminate\Http\Response
     */
    public function show(HomeWorkDiary $homeWorkDiary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HomeWorkDiary  $homeWorkDiary
     * @return \Illuminate\Http\Response
     */
    public function edit(HomeWorkDiary $homeWorkDiary)
    {
        if (isHeadOfficeEmp() || isSuperAdmin()) {
            $branches = Branch::all();
            $states = State::all();
            $comclasses = ComClass::all();
            $sections = Section::all();
            $academic_years = AcademicYear::all();
        } else {
            $branches = Branch::where('branch_id', get_branch_id());
            $states = State::all();
            $classdata = BranchClass::where('branch_id', get_branch_id())->with(['com_classes'])->get();
            $i = 0;
            foreach ($classdata as $class) {
                $comclasses[$i]['id'] = $class->class_id;
                $comclasses[$i]['class_name'] = $class->com_classes->class_name;
                $i++;
            }
            $sections = Section::all();
            $academic_years = AcademicYear::all();
        }
        return view('homeworkdiary.index', compact(['academic_years','states','branches','comclasses','sections','homeWorkDiary']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HomeWorkDiary  $homeWorkDiary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HomeWorkDiary $homeWorkDiary)
    {
        $request->validate([
            'state_id' => 'required',
            'branch_id' => 'required',
            'class_id' => 'required',
            'academic_year_id' => 'required',
            'homework_date' => 'required',
            //'remarks' => 'required',
            'updated_by' => 'required',
        ]);

        $homeWorkDiary->update($request->all());

        return redirect()->route('homeWorkDiary.index')->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HomeWorkDiary  $homeWorkDiary
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomeWorkDiary $homeWorkDiary)
    {
        try {
            return $homeWorkDiary->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
