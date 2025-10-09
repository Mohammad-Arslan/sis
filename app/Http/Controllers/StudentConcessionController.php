<?php

namespace App\Http\Controllers;

use App\Models\StudentConcession;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StudentConcessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StudentConcession::where(
                'student_id',
                $request->student
            )->with(['student', 'academic_year', 'fee_charge.fee_charges_type', 'fee_concession.fee_concession_type'])->get();

            return DataTables::of($data)
                ->addColumn('academic_year', function ($row) {
                    return isset($row->academic_year->title) ? $row->academic_year->title : 'Not selected';
                })
                ->addColumn('fee_charge', function ($row) {
                    return $row->fee_charge->fee_charges_type->name . ' (' . $row->fee_charge->amount . ' PKR)';
                })
                ->addColumn('fee_concession', function ($row) {
                    return $row->fee_concession->fee_concession_type->name . ' (' . $row->fee_concession->concession_percentage . ' %)';
                })
                ->addColumn('from_date', function ($row) {
                    return $row->start_date ;
                })
                ->addColumn('to_date', function ($row) {
                    return $row->end_date ;
                })
                ->addColumn('action', function ($row) {
                    return view('students.concessions._actions', ['row' => $row]);
                })
                ->rawColumns(['fee_charge', 'action'])
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
        $data = StudentConcession::where('fee_charge_id',$request->fee_charge_id)->where('fee_concession_id',$request->fee_concession_id)->where('start_date',$request->start_date)->where('end_date',$request->end_date)->where('student_id',$request->student_id)->first();
        if($data){
            return redirect(route('students.edit', $request->student_id) . '?tab=concession')->with('error', 'Duplicate Entries not allowed.');
        }

       $validated = $request->validate([
            "fee_charge_id" => "required",
            "academic_year_id" => "required", 
            "fee_concession_id" => "required",
            "start_date" => "required|date|after_or_equal:today",
            "end_date" => "required|date|after:start_date",
            "student_id" => "required"
        ], [
            'fee_charge_id.required' => 'Please select a fee charge',
            'academic_year_id.required' => 'Please select an academic year',
            'fee_concession_id.required' => 'Please select a fee concession',
            'start_date.required' => 'Please select a start date',
            'start_date.after_or_equal' => 'Start date must be today or a future date',
            'end_date.required' => 'Please select an end date',
            'end_date.after' => 'End date must be after the start date',
            'student_id.required' => 'Student ID is required',
        ]);

        $input['is_valid'] = 0;
        StudentConcession:: where('student_id', $request->student_id)->update($input);
        StudentConcession::create($request->all());
        return redirect(route('students.edit', $request->student_id) . '?tab=concession')->with('success', 'Concssion added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentConcession  $studentConcession
     * @return \Illuminate\Http\Response
     */
    public function show(StudentConcession $studentConcession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentConcession  $studentConcession
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentConcession $studentConcession)
    {
        $concession = $studentConcession->toArray();
        return redirect(route('students.edit', $studentConcession->student_id). '?tab=concession&e='.$studentConcession->id)->with('concession',$concession);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentConcession  $studentConcession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentConcession $studentConcession)
    {
        $concession_record = StudentConcession::find($studentConcession->id);
        $request->validate([
            "academic_year_id" => "required",
            "fee_charge_id" => "required",
            "fee_concession_id" => "required",
            "start_date" => "required",
            "end_date" => "required",
            "student_id" => "required"
        ]);
        $input = $request->all();
        $concession_record->update($input);
        return redirect(route('students.edit', $request->student_id) . '?tab=concession')->with('success', 'Concssion record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentConcession  $studentConcession
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentConcession $studentConcession)
    {
        try {
            return $studentConcession->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
