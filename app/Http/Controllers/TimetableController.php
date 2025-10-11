<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Models\Employee;
use App\Models\Class;
use App\Models\Branch;
use App\Models\Section;
use App\Models\Subject;
use App\Models\BranchClassSection;
use App\Models\ClassSubject;
use App\Models\ComClass;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\ClassTeacher;
use Carbon\Carbon;
use Log;

class TimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $userId = Auth::user()->id;
        
        // Debug: Log user information
        Log::info('TimetableController::index - User info', [
            'user_id' => $userId,
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'has_teacher_role' => Auth::user()->hasRole('teacher'),
            'has_subject_coordinator_role' => Auth::user()->hasRole('subject_coordinator'),
            'has_school_coordinator_role' => Auth::user()->hasRole('school-coordinator'),
            'has_super_admin_role' => Auth::user()->hasRole('super_admin'),
        ]);

        // TEMPORARY: Allow all users to see timetables for testing
        // TODO: Remove this bypass once roles are properly configured
        $timetables = Timetable::with(['employee', 'class', 'section', 'subject'])->get();
        Log::info('Temporary bypass - fetching all timetables', ['count' => $timetables->count()]);

        /* ORIGINAL ROLE-BASED LOGIC (COMMENTED OUT FOR TESTING)
        // Check if the user is super_admin
        if (Auth::user()->hasRole('super_admin')) {
            // Fetch all timetables
            $timetables = Timetable::with(['employee', 'class', 'section', 'subject'])->get();
            \Log::info('Super admin - fetching all timetables', ['count' => $timetables->count()]);
        } else if (Auth::user()->hasRole('subject_coordinator') || Auth::user()->hasRole('school-coordinator') || Auth::user()->hasRole('teacher')) {
            // Fetch the employee_id associated with the logged-in user's id
            $employeeId = DB::table('employees')
                ->where('user_id', $userId)
                ->value('id');

            \Log::info('Teacher/Coordinator - employee_id found', ['employee_id' => $employeeId]);

            if (Auth::user()->hasRole('subject_coordinator') || Auth::user()->hasRole('school-coordinator')) {
                // Fetch timetables created by the coordinator or associated with the coordinator
                $timetables = Timetable::with(['employee', 'class', 'section', 'subject'])
                    ->where('user_id', $userId)
                    ->orWhereHas('employee', function ($query) use ($employeeId) {
                        $query->where('id', $employeeId);
                    })->get();
                \Log::info('Coordinator - fetching timetables', ['count' => $timetables->count()]);
            } else {
                // Fetch timetables associated with the logged-in teacher's employee_id
                $timetables = Timetable::with(['employee', 'class', 'section', 'subject'])
                    ->whereHas('employee', function ($query) use ($employeeId) {
                        $query->where('id', $employeeId);
                    })->get();
                \Log::info('Teacher - fetching timetables', ['count' => $timetables->count()]);
            }
        } else {
            // For other roles, fetch no timetables
            $timetables = collect();
            \Log::warning('User has no timetable roles - no timetables fetched');
        }
        */

        // Debug: Log the final timetables count
        Log::info('Final timetables count', ['count' => $timetables->count()]);

        if (!isset($timetables) || $timetables->isEmpty()) {
            // If there are no timetables, set $events to an empty array and set a message
            $events = [];
            $message = 'No timetables available.';
            Log::info('No timetables found - setting empty events');
        } else {
            // Iterate through timetables and populate $events
            $events = [];
            foreach ($timetables as $timetable) {
                // Check if all required relationships exist
                if ($timetable->employee && $timetable->class && $timetable->section && $timetable->subject) {
                    $events[] = [
                        'id' => $timetable->id,
                        'title' => 'Teacher: ' . $timetable->employee->preferred_name . ' | Class Nature: ' . $timetable->class_nature . ' | Class: ' . $timetable->class->class_name . ' | Section: ' . $timetable->section->section_name . ' | Subject: ' . $timetable->subject->subject_name . ' | Time: ' . date('h:i A', strtotime($timetable->start_datetime)) . ' to ' . date('h:i A', strtotime($timetable->end_datetime)),
                        'start' => $timetable->start_datetime,
                        'end' => $timetable->end_datetime,
                    ];
                } else {
                    Log::warning('Timetable missing relationships', [
                        'timetable_id' => $timetable->id,
                        'has_employee' => $timetable->employee ? 'yes' : 'no',
                        'has_class' => $timetable->class ? 'yes' : 'no',
                        'has_section' => $timetable->section ? 'yes' : 'no',
                        'has_subject' => $timetable->subject ? 'yes' : 'no'
                    ]);
                }
            }
            $message = "";
            Log::info('Events created successfully', ['events_count' => count($events)]);
        }

        return view('timetables.index', compact('events', 'message'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     $selectedBranch = Auth::user()->employee->branch;
    //     // dd($selectedBranch->id);

    //     // Retrieve the employees related to the selected branch
    //     $employees = Employee::where('branch_id', $selectedBranch->id)
    //         ->whereNotNull('preferred_name')
    //         ->get();
    //     // dd($employees);

    //     $classes = [];
    //     $sections = [];
    //     $subjects = [];


    //     // Check if employee_id is present in the request (selected teacher)
    //     // If yes, fetch classes for the selected teacher
    //     if ($employeeId = request('employee_id')) {
    //         $classes = $this->getClassesByTeacher($employeeId);
    //         $sections = $this->getSectionbyClasses($employeeId);
    //         $sections = $this->getSubjectbyClasses($employeeId);


    //     }
    //     // Retrieve the list of branches (you can modify this query as needed)
    //     $branches = Branch::all();


    //     return view('timetables.create', [
    //         'branches' => $branches,
    //         'employees' => $employees,
    //         'selectedBranch' => $selectedBranch,
    //         'classes' => $classes,
    //         'sections' => $sections,
    //         'subjects' => $subjects,
    //     ]);

    // }

    public function create()
    {
        if (Auth::user()->hasRole('super_admin')) {
            // Fetch all branches if the user is a super admin
            $branches = Branch::all();
        } else {
            // Fetch the branch associated with the authenticated user
            $selectedBranch = Auth::user()->employee->branch;
            $branches = [$selectedBranch];
        }

        // Retrieve the employees related to the selected branch or all employees if super admin
        if (Auth::user()->hasRole('super_admin')) {
            $employees = Employee::whereNotNull('preferred_name')->get();
        } else {
            $employees = Employee::where('branch_id', $selectedBranch->id)
                ->whereNotNull('preferred_name')
                ->get();
        }

        $classes = [];
        $sections = [];
        $subjects = [];

        // If yes, fetch classes for the selected teacher
        if ($employeeId = request('employee_id')) {
            $classes = $this->getClassesByTeacher($employeeId);
            $sections = $this->getSectionbyClasses($employeeId);
            $sections = $this->getSubjectbyClasses($employeeId);
        }

        return view('timetables.create', [
            'branches' => $branches,
            'employees' => $employees,
            'selectedBranch' => isset($selectedBranch) ? $selectedBranch : null,
            'classes' => $classes,
            'sections' => $sections,
            'subjects' => $subjects,
        ]);
    }
  public function getEmployeesByBranch($branchId)
    {
        $employees = Employee::where('branch_id', $branchId)->get();
        return response()->json($employees);
    }
    public function getClassesByTeacher(Request $request)
    {
        // dd($request->all());
        $employeeId = $request->employee_id;
        // dd($employeeId);


        // Fetch classes based on the selected teacher
        $classes = Employee::where('id', $employeeId)
            ->with('class_teachers.branch_class_section.com_classes')
            ->distinct()
            ->get();
        $classes = $classes->pluck('class_teachers')->flatten()->pluck('branch_class_section')->unique('id')->pluck('com_classes')->unique('id');
        return response()->json(['classes' => $classes]);
    }

    public function getSectionbyClasses(Request $request)
    {
        // dd($request->all());
        $employeeId = $request->employee_id;
        // dd($employeeId);


        // Fetch classes based on the selected teacher
        $sections = Employee::where('id', $employeeId)
            ->with('class_teachers.branch_class_section.sections')
            ->get();
        $sections = $sections->pluck('class_teachers')->flatten()->pluck('branch_class_section')->unique('id')->pluck('sections')->unique('id');
        return response()->json(['sections' => $sections]);
    }
    public function getSubjectbyClasses(Request $request)
    {
        // dd($request->all());
        $employeeId = $request->employee_id;
        // dd($employeeId);


        // Fetch classes based on the selected teacher
        $subjects = Employee::where('id', $employeeId)
            ->with('class_teachers.subject')
            ->get();
        $subjects = $subjects->pluck('class_teachers')->flatten()->pluck('subject')->unique('id');
        return response()->json(['subjects' => $subjects]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'branch_id' => 'required',
            'employee_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            // 'start_datetime' => 'required',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'class_nature' => 'required|in:Substitution,Homeroom,Subject Specialist',
        ]);

        Timetable::create([
            'branch_id' => $request->branch_id,
            'employee_id' => $request->employee_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'day' => $request->day,
            // 'start_datetime' => $request->start_datetime,
            'start_datetime' => $request->date . ' ' . $request->start_time,
            'end_datetime' => $request->date . ' ' . $request->end_time,
            'class_nature' => $request->class_nature,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('timetables.index')->with('success', 'Timetable entry created successfully.');
    }

    // public function edit($id)
    // {
    //     $timetable = Timetable::findOrFail($id);

    //     // Check if the authenticated user is a coordinator
    //     if (Auth::user()->hasRole('subject_coordinator|school-coordinator|super_admin')) {
    //         // Check if the user has an associated employee and branch
    //         if (Auth::user()->employee && Auth::user()->employee->branch) {
    //             $selectedBranch = Auth::user()->employee->branch;

    //             $employees = Employee::where('branch_id', $selectedBranch->id)
    //                 ->whereNotNull('preferred_name')
    //                 ->get();

    //             $classes = $timetable->class;
    //             $sections = $timetable->section;
    //             $subjects = $timetable->subject;

    //             $branches = Branch::all();

    //             return view('timetables.edit', compact('timetable', 'branches', 'employees', 'selectedBranch', 'classes', 'sections', 'subjects'));
    //         } else {
    //             return redirect()->back()->with('error', 'Unable to fetch timetable data. Please make sure you are associated with a branch.');
    //         }
    //     } else {
    //         return redirect()->back()->with('error', 'You are not authorized to edit timetables.');
    //     }
    // }

    public function edit($id)
{
    $timetable = Timetable::findOrFail($id);

    // Retrieve the branch directly from the timetable's branch_id
    $selectedBranch = Branch::find($timetable->branch_id);

    // Check if the authenticated user is a coordinator
    if (Auth::user()->hasRole('subject_coordinator|school-coordinator|super_admin')) {
        // Proceed with fetching other data since we have the selected branch now
        $employees = Employee::where('branch_id', $selectedBranch->id)
            ->whereNotNull('preferred_name')
            ->get();

        $classes = $timetable->class;
        $sections = $timetable->section;
        $subjects = $timetable->subject;

        $branches = Branch::all();

        return view('timetables.edit', compact('timetable', 'branches', 'employees', 'selectedBranch', 'classes', 'sections', 'subjects'));
    } else {
        return redirect()->back()->with('error', 'You are not authorized to edit timetables.');
    }
}


    public function update(Request $request, $id)
    {
        $request->validate([
            'branch_id' => 'required',
            'employee_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            // 'start_datetime' => 'required',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'class_nature' => 'required|in:Substitution,Homeroom,Subject Specialist',
        ]);

        $timetable = Timetable::findOrFail($id);

        $timetable->update([
            'branch_id' => $request->branch_id,
            'employee_id' => $request->employee_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'day' => $request->day,
            // 'start_datetime' => $request->start_datetime,
            'start_datetime' => $request->date . ' ' . $request->start_time,
            'end_datetime' => $request->date . ' ' . $request->end_time,
            'class_nature' => $request->class_nature,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('timetables.index')->with('success', 'Timetable entry updated successfully.');
    }

    public function destroy($id)
    {
        $timetable = Timetable::findOrFail($id);

        if (Auth::user()->hasRole('subject_coordinator|school-coordinator|super_admin')) {
            // Delete the timetable entry
            $timetable->delete();

            return response()->json(['message' => 'Timetable entry deleted successfully.']);
        } else {
            // If the user is not authorized, return an error response
            return response()->json(['error' => 'You are not authorized to delete this timetable entry.'], 403);
        }
    }


}
