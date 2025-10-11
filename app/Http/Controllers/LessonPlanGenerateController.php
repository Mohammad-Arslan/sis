<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Attachment;
use App\Models\Branch;
use App\Models\BranchAcademicYear;
use App\Models\BranchClass;
use App\Models\BranchClassSection;
use App\Models\ClassStudent;
use App\Models\ClassStudentSubject;
use App\Models\ClassTeacher;
use App\Models\ComClass;
use App\Models\Employee;
use App\Models\LessonPlan;
use App\Models\LessonPlanGenerate;
use App\Models\Section;
use App\Models\State;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\StudentLearningOutcome;
use App\Models\Subject;
use App\Models\Term;
use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\SendEmail;

class LessonPlanGenerateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Show lesson plans
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Load necessary data for dynamic select fields
        $branches = Branch::all();
        $academicYears = AcademicYear::all();
        $comClasses = ComClass::all();
        $terms = Term::all();
        $weeks = Week::all();
        $states = State::all();
        $sections = Section::all();
        $subjects = Subject::all();

        return view('generate-lesson-plans.create', compact('branches', 'academicYears', 'comClasses', 'terms', 'weeks', 'states', 'sections', 'subjects'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the form data
        $validatedData = $request->validate([
            'branch_id' => 'required',
            'academic_year_id' => 'required',
            'com_class_id' => 'required',
            'subject_id' => 'required',
            'section_id' => 'required',
            'state_id' => 'required',
            'term_id' => 'required',
            'week_id' => 'required',
            'day' => 'required',
            'topic' => 'required',
            'lesson_plan_details' => 'required',
            'bocc_link' => 'required',
        ]);

        // Create a new LessonPlanGenerate instance with the validated data
        LessonPlanGenerate::create($validatedData);
        // dd($validatedData);

        // Redirect to a success page or other actions
        return redirect()->route('generate_lesson.create')->with('success', 'Lesson Plan created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LessonPlanGenerate  $lessonPlan
     * @return \Illuminate\Http\Response
     */
    public function show(LessonPlanGenerate $lessonPlan)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LessonPlanGenerate  $lessonPlan
     * @return \Illuminate\Http\Response
     */
    public function edit(LessonPlanGenerate $lessonPlan)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LessonPlanGenerate  $lessonPlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LessonPlanGenerate $lessonPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LessonPlanGenerate  $lessonPlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(LessonPlanGenerate $lessonPlan)
    {
        //
    }

}
