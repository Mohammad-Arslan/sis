<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\TeacherObservation;
use App\Models\ObservationDetail;
use App\Models\Branch;
use App\Models\Employee;
use Carbon\Carbon;




class TeacherObservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $observations = TeacherObservation::with('branch', 'teacher', 'observationDetails')->get();


        return view('teacher_evaluation.index', compact('observations'));
    }

    
    public function create()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super_admin');
        
        // For super_admin, show all branches
        if ($isSuperAdmin) {
            $branches = Branch::all();
            $selectedBranch = null; // No pre-selected branch for super_admin
            $employees = collect(); // Empty collection initially
            
            return view('teacher_evaluation.create', [
                'branches' => $branches,
                'employees' => $employees,
                'selectedBranch' => $selectedBranch,
                'isSuperAdmin' => $isSuperAdmin,
            ]);
        }
        
        // For non-super_admin users, check employee record
        if (!$user->employee) {
            return redirect()->back()->with('error', 
                'Error: Your user account is not associated with an employee record. ' .
                'Please contact the administrator to set up your employee profile.'
            );
        }
        
        $selectedBranch = $user->employee->branch;
        
        // Check if employee has a branch
        if (!$selectedBranch) {
            return redirect()->back()->with('error', 
                'Error: Your employee record is not associated with any branch. ' .
                'Please contact the administrator to assign you to a branch.'
            );
        }

        // For non-super_admin, only show their branch
        $branches = collect([$selectedBranch]); // Only their branch
        $employees = Employee::where('branch_id', $selectedBranch->id)
            ->whereNotNull('preferred_name')
            ->whereHas('designation', function($query) {
                $query->where('designation_name', 'Teacher');
            })
            ->get();

        // Check if branch has class sections configured
        $branchClassSections = \App\Models\BranchClassSection::where('branch_id', $selectedBranch->id)->count();
        
        if ($branchClassSections == 0) {
            return view('teacher_evaluation.create', [
                'branches' => $branches,
                'employees' => $employees,
                'selectedBranch' => $selectedBranch,
                'isSuperAdmin' => $isSuperAdmin,
            ])->with('warning', 
                "Warning: The selected branch '{$selectedBranch->br_name}' does not have any class sections configured. " .
                "Please set up branch class sections before creating teacher observations. " .
                "Go to Branch Management > Classes > Class Sections to configure this."
            );
        }

        return view('teacher_evaluation.create', [
            'branches' => $branches,
            'employees' => $employees,
            'selectedBranch' => $selectedBranch,
            'isSuperAdmin' => $isSuperAdmin,
        ]);
    }



    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'branch_id' => 'required|exists:branches,id',
                'employee_id' => 'required|exists:employees,id',
                'evaluation_user_id' => 'required|exists:users,id',
            ]);

            // Create a new Teacher Observation record using the request data.
            TeacherObservation::create([
                'branch_id' => $request->input('branch_id'),
                'employee_id' => $request->input('employee_id'),
                'evaluation_user_id' => $request->input('evaluation_user_id'),
            ]);

            // Redirect to the index page or a success page.
            return redirect()->route('teacher_evaluation.index')->with('success', 'Teacher Observation created successfully');
            
        } catch (QueryException $e) {
            // Handle foreign key constraint violations
            if ($e->getCode() == 23000) {
                $errorMessage = "Database constraint error: ";
                
                if (strpos($e->getMessage(), 'teacher_observations_branch_id_foreign') !== false) {
                    $errorMessage .= "The selected branch (ID: {$request->input('branch_id')}) does not have any class sections configured. ";
                    $errorMessage .= "Please ensure that branch class sections are set up for this branch before creating teacher observations.";
                } elseif (strpos($e->getMessage(), 'teacher_observations_employee_id_foreign') !== false) {
                    $errorMessage .= "The selected employee (ID: {$request->input('employee_id')}) does not exist.";
                } elseif (strpos($e->getMessage(), 'teacher_observations_evaluation_user_id_foreign') !== false) {
                    $errorMessage .= "The selected evaluator (ID: {$request->input('evaluation_user_id')}) does not exist.";
                } else {
                    $errorMessage .= "A foreign key constraint failed. Please check that all referenced records exist.";
                }
                
                return redirect()->back()->with('error', $errorMessage)->withInput();
            }
            
            // Re-throw other database exceptions
            throw $e;
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($teacher_observation_id)
    {
        $observationDetails = ObservationDetail::with('ratings.questionDimension')
            ->where('teacher_observation_id', $teacher_observation_id)
            ->get();

        return view('teacher_evaluation.show', compact('observationDetails'));
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
        //
    }

    /**
     * Get teachers for a specific branch (for super_admin)
     */
    public function getTeachersByBranch($branchId)
    {
        $teachers = Employee::where('branch_id', $branchId)
            ->whereNotNull('preferred_name')
            ->whereHas('designation', function($query) {
                $query->where('designation_name', 'Teacher');
            })
            ->get(['id', 'preferred_name']);

        return response()->json($teachers);
    }
}
