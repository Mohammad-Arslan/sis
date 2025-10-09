<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\FamilyInformation;
use App\Models\Guardian;
use App\Models\Relation;
use App\Models\State;
use App\Models\Student;
use App\Models\Language;
use App\Models\Nationality;
use App\Models\Religion;
use App\Models\SiblingInformation;
use App\Models\StudentAddress;
use App\Models\Town;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Guardian::where('student_id', $request->student)
                ->with(['students', 'relation'])
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('full_name', function ($row) {
                    $student = $row->students;
                    if (!$student)
                        return '-';

                    $firstName = $student['first_name'] ?? '';
                    $middleName = $student['middle_name'] ?? '';
                    $lastName = $student['last_name'] ?? '';

                    return trim("{$firstName} {$middleName} {$lastName}");
                })

                ->addColumn('employee_no', function ($row) {
                    return $row->employee_no ?? '-';
                })

                ->addColumn('emp_branch_name', function ($row) {
                    if ($row->employee_no) {
                        $employee = Employee::with('branch')->where('employee_id', $row->employee_no)->first();
                        return $employee->branch->br_name ?? '-';
                    }
                    return '-';
                })

                ->addColumn('relation_name', function ($row) {
                    return $row->relation['relation_name'] ?? '-';
                })

                ->addColumn('CNIC', function ($row) {
                    return $row->CNIC ?? '-';
                })

                ->addColumn('mobile', function ($row) {
                    return $row->mobile ?? '-';
                })

                ->addColumn('email', function ($row) {
                    return $row->email ?? '-';
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ?? '-';
                })

                ->addColumn('action', function ($row) use ($request) {
                    return view('students.guardian_actions', [
                        'row' => $row,
                        'request' => $request
                    ])->render();
                })

                ->rawColumns(['employee_no', 'action']) // Only columns containing HTML need to be raw
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'relation_id' => 'required',
            'guardian_name' => 'required|max:255',
            'CNIC' => 'required|max:15|regex:/^[0-9]{5}-[0-9]{7}-[0-9]$/',
            'mobile' => 'required|max:255',
            'is_parent' => 'required',
            'employee_no' => 'required_if:is_parent,yes|max:10',
            'student_id' => 'required',
            'email' => 'required | email | max:255',
        ]);

        \DB::beginTransaction();

        $siblings = Student::with('first_guardian.family.children')->whereHas('first_guardian', function ($query) use ($request) {
            $query->where('CNIC', $request->CNIC);
            $query->whereHas('family');
        })->oldest('created_at');
        $no_of_siblings = count($siblings->get());

        $guardian = Guardian::create($request->all());

        if ($no_of_siblings) {
            $sibling_no = count($siblings->get()->toArray()[0]['first_guardian']['family']['children']);
            $siblings->first()->first_guardian->family->children()->create([
                'student_id' => $request->student_id,
                'sibling_no' => $sibling_no + 1
            ]);
            // $guardian->family()->children()->create([
            // ]);
        } else {
            $family = $guardian->family()->create([
                'family_no' => rand(100000, 999999),
                'CNIC' => $request->CNIC
            ]);
            $family->children()->create([
                'student_id' => $request->student_id,
                'sibling_no' => 1
            ]);
        }

        \DB::commit();

        return redirect(route('students.edit', $request->student_id) . '?tab=guardian')->with('success', 'Guardian added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Guardian $guardian
     * @return \Illuminate\Http\Response
     */
    public function show(Guardian $guardian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Guardian $guardian
     * @return \Illuminate\Http\Response
     */
    public function edit(Guardian $guardian)
    {
        $branches = Branch::all();
        $relation = Relation::get();
        $student = Student::find($guardian->student_id);
        // $studentAddress = $student->load('student_address')->student_address;

        $countries = Country::get();
        $states = State::get();
        $cities = City::get();
        $towns = Town::get();

        $languages = Language::get();
        $nationalities = Nationality::get();
        $religions = Religion::get();

        return view('students.add_student', [
            'tab' => 'guardian',
            'branches' => $branches,
            'student' => $student,
            // 'student_address' => $studentAddress,
            'relations' => $relation,
            'guardian' => $guardian,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'towns' => $towns,
            'languages' => $languages,
            'nationalities' => $nationalities,
            'religions' => $religions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Guardian $guardian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guardian $guardian)
    {
        $request->validate([
            'relation_id' => 'required',
            'guardian_name' => 'required|max:255',
            'CNIC' => 'required|max:15|regex:/^[0-9]{5}-[0-9]{7}-[0-9]$/',
            'mobile' => 'required|max:255',
            'is_parent' => 'required',
            'employee_no' => 'required_if:is_parent,yes|max:10',
            'student_id' => 'required',
            'email' => 'required | email',
        ]);
        // dd($guardian->CNIC, $request->CNIC);
        if ($guardian->CNIC == $request->CNIC)
            $guardian->update($request->all());
        else {
            \DB::beginTransaction();

            $siblings = Student::with('first_guardian.family.children')->whereHas('first_guardian', function ($query) use ($request) {
                $query->where('CNIC', $request->CNIC);
                $query->whereHas('family');
            })->oldest('created_at');
            // dd((int)count($siblings->get()));
            $no_of_siblings = (int) count($siblings->get());
            // dd($siblings->get()->toArray());
            $guardian->update($request->all());

            if ($no_of_siblings) {
                // dd($no_of_siblings);
                $sibling_info = SiblingInformation::where('student_id', $request->student_id)->delete();
                $sibling_no = count($siblings->get()->toArray()[0]['first_guardian']['family']['children']);
                $siblings->first()->first_guardian->family->children()->create([
                    'student_id' => $request->student_id,
                    'sibling_no' => $sibling_no + 1
                ]);
            } else {
                SiblingInformation::where('student_id', $request->student_id)->delete();

                FamilyInformation::where('guardian_id', $guardian->id)->delete();
                $guardian->family()->create([
                    'family_no' => rand(100000, 999999),
                    'CNIC' => $request->CNIC
                ]);
                $guardian->family->children()->create([
                    'student_id' => $request->student_id,
                    'sibling_no' => 1
                ]);
            }

            \DB::commit();
        }
        return redirect(route('students.edit', $guardian->student_id) . '?tab=guardian')->with('success', 'Guardian updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Guardian $guardian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guardian $guardian)
    {
        try {
            $guardian_fam = FamilyInformation::where('guardian_id', $guardian->id)->first();
            // dd($guardian_fam);
            if ($guardian_fam) {
                SiblingInformation::where('family_information_id', $guardian_fam->id)->delete();
                FamilyInformation::where('guardian_id', $guardian->id)->delete();
            }
            return $guardian->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
