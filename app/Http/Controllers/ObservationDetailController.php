<?php

namespace App\Http\Controllers;

use App\Models\ClassTeacher;
use App\Models\ObservationDetail;
use Auth;
use Illuminate\Http\Request;
use App\Models\TeacherObservation;
// use App\Models\ComClass;
// use App\Models\Section;
use App\Models\BranchClassSection;
use App\Models\QuestionDimension;
use App\Models\Rating;


use App\Models\ClassSubject;
use App\Models\AcademicYear;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class ObservationDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $teacherObservations = TeacherObservation::find($request->id);
        $academicYears = AcademicYear::all();

        // Get the employee_id from the TeacherObservation model
        $employeeId = $teacherObservations->employee_id;

        // dd($employeeId);

        $classes = Employee::where('id', $employeeId)
            ->with('class_teachers.branch_class_section.com_classes')
            ->distinct()
            ->get();

        $classTeacher = new ClassTeacher();

        $sections = Employee::where('id', $employeeId)
            ->with('class_teachers.branch_class_section.sections')
            ->get();
        // dd($sections);

        $observations = ObservationDetail::where('teacher_observation_id', $teacherObservations->id)->select('class_id', 'section_id')->get();
        // dd($observations);
        $subjects = Employee::where('id', $employeeId)
            ->with('class_teachers.subject')
            ->get();
        // dd($subjects);

        return view(
            'observation_details.create',
            compact(
                'teacherObservations',
                'academicYears',
                'classes',
                'sections',
                'subjects'
            )
        );
    }




    public function store(Request $request)
    {
        $request->validate([
            'teacher_observation_id' => 'required|exists:teacher_observations,id',
            'academic_years_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|integer',
            'section_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'part_of_period_observed' => 'required|in:FULL PERIOD,FIRST 20 MIN,MID 20 MIN,LAST 20 MIN',
            'observation_date' => 'required',
        ]);

        // Check if the referenced class_id exists in branch_class_sections
        $classExists = DB::table('branch_class_sections')->where('id', $request->class_id)->exists();
        if (!$classExists) {
            return back()->withInput()->withErrors([
                'class_id' => 'The selected class is invalid or does not exist in the system. Please select a valid class.'
            ]);
        }

        // Check if the referenced section_id exists in sections
        $sectionExists = DB::table('sections')->where('id', $request->section_id)->exists();
        if (!$sectionExists) {
            return back()->withInput()->withErrors([
                'section_id' => 'The selected section is invalid or does not exist in the system. Please select a valid section.'
            ]);
        }

        // Check if the referenced subject_id exists in class_subjects
        $subjectExists = DB::table('class_subjects')->where('id', $request->subject_id)->exists();
        if (!$subjectExists) {
            return back()->withInput()->withErrors([
                'subject_id' => 'The selected subject is invalid or does not exist in the system. Please select a valid subject.'
            ]);
        }

        try {
            $data = ObservationDetail::create([
                'teacher_observation_id' => $request->teacher_observation_id,
                'academic_years_id' => $request->academic_years_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'part_of_period_observed' => $request->part_of_period_observed,
                'observation_date' => $request->observation_date,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Check for foreign key constraint violation
            if ($e->getCode() == 23000) {
                return back()->withInput()->withErrors([
                    'database' => 'Unable to save observation detail due to invalid reference data. Please ensure all selected values are valid and try again.'
                ]);
            }
            // For other database errors, show a generic error
            return back()->withInput()->withErrors([
                'database' => 'An unexpected error occurred while saving the observation detail. Please try again or contact support.'
            ]);
        }

        // Redirect to the competency view with the necessary data
        return redirect()->route('observation_details.competency', ['id' => $data->id]);
    }

    public function getRatingView(Request $request, $id)
    {
        // dd($request->all());
        // Retrieve the observation details for the given teacher_observation_id
        $questionsdim = QuestionDimension::with('answerDimensions')->get();
        // dd($questionsdim);
        $observationDetails = ObservationDetail::where('id', $id)->first();
        // dd($observationDetails);
        $selectedClass = null;
        $selectedSection = null;
        $selectedSubject = null;
        $createdAtDate = null;

        if ($observationDetails) {
            $teacherObservation = TeacherObservation::find($observationDetails->teacher_observation_id);
            $teacher = Employee::find($teacherObservation->employee_id);
            $selectedClass = $observationDetails->com_classes->class_name;
            $selectedSection = $observationDetails->sections->section_name;
            $createdAtDate = $observationDetails->observation_date;

            $classTeacher = ClassTeacher::where('employee_id', $teacher->id)->first();
            $subjectName = $classTeacher->subject->subject_name;
            $preferredName = $teacher->preferred_name;

        }
        // dd($observationDetails);

        return view('observation_details.competency')
            ->with('teacherObservations', ObservationDetail::find($id))
            ->with('academicYears', AcademicYear::all())
            ->with('selectedClass', $selectedClass)
            ->with('selectedSection', $selectedSection)
            ->with('selectedSubject', $selectedSubject)
            ->with('createdAtDate', $createdAtDate)
            ->with('preferredName', $preferredName)
            ->with('subjectName', $subjectName)
            ->with('questionsdim', $questionsdim);
    }




    public function saveDimensionRatings(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'id' => 'required|exists:observation_details,id',
            'nextobservationdate' => 'nullable',
        ]);

        $observationDetail = ObservationDetail::where('id', $request->id)->first();

        if (!$observationDetail) {
            return back()->with('error', 'Observation detail not found.');
        }

        // Save ratings for each question and answer dimension
        $ratings = $request->input('ratings');
        foreach ($ratings as $questionId => $questionRatings) {
            foreach ($questionRatings as $answerId => $rating) {
                $ratingSum = array_sum($rating);
                // dd($ratingSum);

                // Create or update the rating in the database
                $ratingModel = Rating::updateOrcreate([
                    'observation_detail_id' => $observationDetail->id,
                    'question_dimension_id' => $questionId,
                    'answer_dimension_id' => $answerId,
                ], [
                    'rating' => $ratingSum,
                ]);

            }

        }

        // Update the planning_preparing_rating column
        $observationDetail->update([
            'nextobservationdate' => $request->nextobservationdate,
        ]);

        return redirect()->back()->with('success', 'Ratings saved successfully.');
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
        //
    }
}
