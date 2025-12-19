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
use App\Models\CurriculumAttainmentTarget;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\LeaveApplication;
use App\Models\LessonPlan;
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

class LessonPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $terms = LessonPlan::with([
                'term',
                'week',
                'com_class',
                'subject',
                'state',
            ])->OrderBy('term_id')->OrderBy('week_id');

            if (! auth()->user()->hasRole('super_admin') && ! auth()->user()->hasRole('network_associate') && auth()->user()->hasPermission('approve-lesson-plan')) {
                $terms = $terms->whereNotNull('approval_status');
            }


            $terms = $this->filteration($request, $terms, 'index');

            $terms = $terms->select('academic_year_id', 'branch_id', 'term_id', 'week_id', 'com_class_id', 'subject_id', 'state_id')
                ->groupBy(['academic_year_id', 'branch_id', 'term_id', 'week_id', 'com_class_id', 'subject_id', 'state_id'])->get();

            $lesson_plans = array();
            foreach ($terms as $week) {
                $week['day_count'] = LessonPlan::with([
                    'term',
                    'week',
                ])->where([
                            ['branch_id', $week->branch_id],
                            ['academic_year_id', $week->academic_year_id],
                            ['com_class_id', $week->com_class_id],
                            ['subject_id', $week->subject_id],
                            ['term_id', $week->term_id],
                            ['week_id', $week->week_id],
                            ['state_id', $week->state_id],
                        ])->get();

                $week['id'] = $week['day_count'][0]['id'];

                if (auth()->user()->hasRole('network_associate')) {
                    $week['statuses'] = $week['day_count']->where('approval_status', 'publish')->pluck('approval_status')->toArray();
                } elseif (auth()->user()->hasRole('academic_head')) {
                    $week['statuses'] = $week['day_count']->whereNotNull('approval_status')->pluck('approval_status')->toArray();
                } else {
                    $week['statuses'] = $week['day_count']->whereIn('approval_status', ['pending_for_approval', 'approved', 'publish'])->pluck('approval_status')->toArray();
                }


                if (in_array('pending_for_approval', $week['statuses'])) {
                    $week['approval_status'] = 'pending_for_approval';
                } elseif (in_array('approved', $week['statuses']) && ! in_array(null, $week['statuses'])) {
                    $week['approval_status'] = 'approved';
                } elseif (in_array('publish', $week['statuses']) && ! in_array(null, $week['statuses'])) {
                    $week['approval_status'] = 'publish';
                }

                if (! auth()->user()->hasRole('super_admin') && ! auth()->user()->hasRole('network_associate') && auth()->user()->hasPermission('approve-lesson-plan')) {
                    $week['day_count'] = $week['day_count']->whereNotNull('approval_status');
                }

                if (! auth()->user()->hasRole('super_admin') && ! auth()->user()->hasRole('network_associate') && auth()->user()->hasPermission('add-lessonplan-taughtdate')) {
                    $week['day_count'] = $week['day_count']->where('approval_status', 'approved');
                }

                if (! auth()->user()->hasRole('super_admin') && ! auth()->user()->hasRole('network_associate') && auth()->user()->hasPermission('add-lessonplan-taughtdate')) {
                    $week['day_count'] = $week['day_count']->where('approval_status', 'publish');
                }

                if (auth()->user()->hasRole('network_associate')) {
                    $week['day_count'] = $week['day_count']->where('approval_status', 'publish');
                }

                if ($week['approval_status'] == 'pending_for_approval') {
                    $week['approved_count'] = count(array_keys($week['statuses'], "approved"));
                } elseif ($week['approval_status'] == 'approved') {
                    $week['approved_count'] = count(array_keys($week['statuses'], "approved"));
                } elseif ($week['approval_status'] == 'publish') {
                    $week['approved_count'] = count(array_keys($week['statuses'], "publish"));
                }

                $week['day_count'] = $week['day_count']->count();
                $lesson_plans[] = $week;
            }

            return DataTables::of($lesson_plans)
                ->addIndexColumn()
                ->addColumn('folder_name', function ($row) {
                    return view('lesson_plan.folder_name', ['row' => $row]);
                })
                ->addColumn('action', function ($row) {
                    return view('lesson_plan.action', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            $data = $this->filterationDropdownData();

            return view('lesson_plan.index', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        if (auth()->user()->hasRole('network_associate')) {
            $data['branches'] = Branch::whereIn('id', auth()->user()['networkAssociates']['branches']->pluck('id')->toArray())->get();
        } elseif (! auth()->user()->hasRole('super_admin') && auth()->user()->hasPermission('add-lessonplan-taughtdate')) {
            $data['branches'] = Branch::where('id', auth()->user()['employee']['branch_id'])->get();
        } else {
            $data['branches'] = Branch::all();
        }

        $data['academic_years'] = AcademicYear::all();
        $data['classes'] = get_teacher_classes()->isNotEmpty() ? get_teacher_classes() : ComClass::all();
        $data['terms'] = Term::all();

        $data['states'] = State::all();
        // $data['curriculum_attainment_targets'] = CurriculumAttainmentTarget::all();

        // $data['curriculum_attainment_targets'] = []; // Initialize an empty array

        // // Check if both class_id and subject_id are selected
        // if (request()->has('class_id') && request()->has('subject_id')) {
        //     $classId = request('class_id');
        //     $subjectId = request('subject_id');

        //     // Retrieve curriculum_attainment_targets based on the selected class and subject
        //     $data['curriculum_attainment_targets'] = CurriculumAttainmentTarget::whereHas('curriculum', function ($query) use ($classId, $subjectId) {
        //         $query->where('class_id', $classId)->where('subject_id', $subjectId);
        //     })->get();
        // }

        /*if (!auth()->user()->hasRole('super_admin')){
            $state_id = 0;
            if (auth()->user()->hasRole('network_associate')){
                $branch_id = get_set_NWABranchId();
                $branch = Branch::with(['contact_information'])->where('id',$branch_id)->first();
                $state_id = isset($branch['contact_information']) ? $branch['contact_information']['state_id'] : 0;
            }else{
                $state_id = $user['employee'] ? $user['employee']['state_id'] : 0;
            }

            $data['states'] = State::where('id',$state_id)->get();
        }*/

        return view('lesson_plan.create', $data);
    }


    public function getCurriculumAttainmentTargets(Request $request)
    {
        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');

        $curriculumAttainmentTargets = CurriculumAttainmentTarget::whereHas('curriculum', function ($query) use ($classId, $subjectId) {
            $query->where('class_id', $classId)->where('subject_id', $subjectId);
        })->get();

        return response()->json($curriculumAttainmentTargets);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $input = $request->all();
        $input['com_class_id'] = $request->class_id;
        $lesson_plan = LessonPlan::create($input);

        return redirect()->route('lesson-plans.edit', $lesson_plan->id)->with('success', 'Form submitted successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LessonPlan  $lessonPlan
     * @return \Illuminate\Http\Response
     */
    public function show(LessonPlan $lessonPlan)
    {
        $lessonPlan->load(['student_learning_outcomes.student_activities']);
        $data['lessonPlan'] = $lessonPlan;

        if ($lessonPlan['language'] == 'urdu') {
            app()->setLocale('ur');
        }

        return view('lesson_plan.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LessonPlan  $lessonPlan
     * @return \Illuminate\Http\Response
     */
    public function edit(LessonPlan $lessonPlan)
    {
        $lessonPlan->load(['term', 'attachments', 'student_learning_outcomes.student_activities']);
        $user = auth()->user();
        if (auth()->user()->hasRole('network_associate')) {
            $data['branches'] = Branch::whereIn('id', auth()->user()['networkAssociates']['branches']->pluck('id')->toArray())->get();
        } elseif (! auth()->user()->hasRole('super_admin') && auth()->user()->hasPermission('add-lessonplan-taughtdate')) {
            $data['branches'] = Branch::where('id', auth()->user()['employee']['branch_id'])->get();
        } else {
            $data['branches'] = Branch::all();
        }

        $data['academic_years'] = AcademicYear::all();
        $data['classes'] = ComClass::all();
        $data['terms'] = Term::all();
        /*$data['academic_years'] = BranchAcademicYear::where('branch_id', $lessonPlan->branch_id)->with('academic_year')->get();
        $data['classes'] = BranchClass::where('branch_id', $lessonPlan->branch_id)->with(['com_classes'])->get();
        $data['terms'] = Term::where('branch_id', $lessonPlan->branch_id)->get();*/


        /*$branch_class_section_ids = BranchClassSection::where('class_id', $lessonPlan->com_class_id)->pluck('id')->toArray();
        $class_student_ids = ClassStudent::whereIn('branch_class_section_id',$branch_class_section_ids)->pluck('id')->toArray();
        $subject_ids = ClassStudentSubject::whereIn('class_student_id',$class_student_ids)->pluck('subject_id')->toArray();
        $data['subjects'] = Subject::whereIn('id',$subject_ids)->get();*/

        $data['subjects'] = listClassSubjects($lessonPlan->com_class_id);

        $end_date = Carbon::parse($lessonPlan['term']['end_date'])->format('Y-m-d');
        $difference = Carbon::parse($lessonPlan['term']['start_date'])->diffInWeeks($end_date);

        $data['weeks'] = Week::whereIn('id', range(1, $difference))->get();
        $data['lessonPlan'] = $lessonPlan;
        $data['states'] = State::all();
        /*if (!auth()->user()->hasRole('super_admin')){
            $state_id = 0;
            if (auth()->user()->hasRole('network_associate')){
                $branch_id = get_set_NWABranchId();
                $branch = Branch::with(['contact_information'])->where('id',$branch_id)->first();
                $state_id = isset($branch['contact_information']) ? $branch['contact_information']['state_id'] : 0;
            }else{
                $state_id = $user['employee'] ? $user['employee']['state_id'] : 0;
            }

            $data['states'] = State::where('id',$state_id)->get();
        }*/

        if ($lessonPlan['language'] == 'urdu') {
            app()->setLocale('ur');
        }

        return view('lesson_plan.create', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LessonPlan  $lessonPlan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LessonPlan $lessonPlan)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            //Student Learning Outcome
            $slo_input = $request->all();
            $slo_input['lesson_plan_id'] = $lessonPlan->id;
            $slo_input[$request->column_name] = $request->editorData;

            $student_learning_outcome = StudentLearningOutcome::find($request->student_learning_outcome_id);
            if (empty($student_learning_outcome)) {
                $student_learning_outcome = StudentLearningOutcome::create($slo_input);
            } else {
                $student_learning_outcome->update($slo_input);
            }

            //Student Activity
            $data['student_activity_id'] = $request->student_activity_id;
            if ($request->column_name != 'teacher_activity') {
                $student_activity = StudentActivity::find($request->student_activity_id);
                $activity_input['student_learning_outcome_id'] = $student_learning_outcome->id;
                $activity_input[$request->column_name] = $request->editorData;
                if (empty($student_activity)) {
                    $student_activity = StudentActivity::create($activity_input);
                } else {
                    $student_activity->update($activity_input);
                }

                $data['student_activity_id'] = $student_activity->id;
            }

            if ($lessonPlan->approval_status == 'approved') {
                $lessonPlan->update(['approval_status' => 'pending_for_approval']);
            }

            DB::commit();

            $data['student_learning_outcome_id'] = $student_learning_outcome->id;


            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully', 'data' => $data]);
        } else {
            if (isset($request->document_name) && $request->hasfile('file')) {
                DB::beginTransaction();
                $file_names = Attachment::pluck('file_name')->unique()->toArray();
                foreach ($request->file as $file_index => $file) {
                    $filename_data['filename'] = $request->document_name;
                    $filename_data['extension'] = $file->getClientOriginalExtension();
                    $filename_data['filenames_arr'] = $file_names;
                    $filename = getUniqueFileName($filename_data);
                    array_push($file_names, $filename);

                    $input['file_name'] = $filename;
                    $filepath = 'images/' . $filename;
                    Storage::disk('s3')->put($filepath, file_get_contents($file));

                    $lessonPlan->attachments()->create([
                        'file_name' => $input['file_name'],
                        'file_type' => $request->attachment_type,
                        'user_id' => auth()->user()->id,
                        'remarks' => ! empty($request->attachment_remarks) ? $request->attachment_remarks : null,
                    ]);
                }
                DB::commit();
            } else {
                if (isset($request['taught_date_to'])) {
                    $request->validate([
                        'taught_date_from' => 'required',
                        'taught_date_to' => 'required|after:taught_date_from',
                    ]);
                }

                $input = $request->all();
                if (isset($request->class_id)) {
                    $input['com_class_id'] = $request->class_id;
                }

                $lessonPlan->update($input);

                if (isset($request['sections'])) {
                    $lessonPlan->sections()->sync($request['sections']);
                }
            }

            return redirect()->back()->with('success', 'Form updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LessonPlan  $lessonPlan
     * @return \Illuminate\Http\Response
     */
    public function destroy(LessonPlan $lessonPlan)
    {
        try {
            return $lessonPlan->delete();

            /*DB::beginTransaction();
            $lesson_plan = LessonPlan::withTrashed()->withTrashed('student_learning_outcomes.student_activities')->where('id',221)->first();
            foreach ($lesson_plan['student_learning_outcomes'] as $student_learning_outcome){
                foreach ($student_learning_outcome['student_activities'] as $student_activity){
                    $student_activity->forceDelete();
                }
                $student_learning_outcome->forceDelete();
            }
            $lesson_plan->forceDelete();
            DB::commit();*/
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function add_slo_row(Request $request)
    {
        $data['slo_count'] = $request->slo_count + 1;
        $data['slo_row'] = view('lesson_plan.slo_row', $data)->render();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully', 'data' => $data]);
    }

    public function add_activity(Request $request)
    {
        $data['slo_no'] = $request->slo_no;
        $data['activity_no'] = $request->activity_no + 1;
        $data['activity_row'] = view('lesson_plan.activity_row', $data)->render();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully', 'data' => $data]);
    }

    public function remove_attachment(Attachment $attachment)
    {
        try {
            $attachment->delete();
            if (Storage::disk('s3')->exists('images/' . $attachment['file_name'])) {
                Storage::disk('s3')->delete('images/' . $attachment['file_name']);
            }

            return true;
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function remove_activity(StudentActivity $activity)
    {
        try {
            return $activity->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function remove_slo(StudentLearningOutcome $studentLearningOutcome)
    {
        try {
            $studentLearningOutcome->student_activities->each->delete();
            return $studentLearningOutcome->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function get_week_days(LessonPlan $lessonPlan)
    {
        try {
            $data['daily_lesson_plans'] = LessonPlan::where([
                ['branch_id', $lessonPlan->branch_id],
                ['academic_year_id', $lessonPlan->academic_year_id],
                ['com_class_id', $lessonPlan->com_class_id],
                ['subject_id', $lessonPlan->subject_id],
                ['term_id', $lessonPlan->term_id],
                ['week_id', $lessonPlan->week_id],
                ['state_id', $lessonPlan->state_id]
            ]);

            if (! auth()->user()->hasRole('super_admin') && ! auth()->user()->hasRole('network_associate') && auth()->user()->hasPermission('approve-lesson-plan')) {
                $data['daily_lesson_plans'] = $data['daily_lesson_plans']->whereNotNull('approval_status');
            }

            if (! auth()->user()->hasRole('super_admin') && ! auth()->user()->hasRole('network_associate') && auth()->user()->hasPermission('add-lessonplan-taughtdate')) {
                $data['daily_lesson_plans'] = $data['daily_lesson_plans']->where('approval_status', 'publish');
            }

            if (auth()->user()->hasRole('network_associate')) {
                $data['daily_lesson_plans'] = $data['daily_lesson_plans']->where('approval_status', 'publish');
            }

            $data['daily_lesson_plans'] = $data['daily_lesson_plans']->OrderBy('day')->get();
            //dd($data['daily_lesson_plans']->toArray());
            $returnData['daily_lesson_plan_rows'] = view('lesson_plan.lesson_plan_rows', $data)->render();

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully', 'data' => $returnData]);
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function update_status(Request $request)
    {
        try {
            $lesson_plan = LessonPlan::find($request->lesson_plan_id);

            if ($request->approval_for == 'week') {
                $update_status = LessonPlan::where([
                    ['branch_id', $lesson_plan->branch_id],
                    ['academic_year_id', $lesson_plan->academic_year_id],
                    ['com_class_id', $lesson_plan->com_class_id],
                    ['subject_id', $lesson_plan->subject_id],
                    ['term_id', $lesson_plan->term_id],
                    ['week_id', $lesson_plan->week_id],
                    ['state_id', $lesson_plan->state_id]
                ]);
                if ($request->status == 'pending_for_approval') {
                    $update_status->whereNull('approval_status');
                    $update_status->update(['approval_status' => $request->status]);
                } else if ($request->status == 'approved') {
                    $update_status->whereIn('approval_status', ['pending_for_approval', 'publish']);
                    $update_status->update(['approval_status' => $request->status, 'approved_by' => auth()->user()->id]);
                }
                //code here for publish.
                else if ($request->status == 'publish') {
                    $update_status->where('approval_status', 'approved');
                    $update_status->update(['approval_status' => $request->status, 'approved_by' => auth()->user()->id]);
                }
            } else if ($request->approval_for == 'day') {
                if ($request->status == 'approved') {
                    $lesson_plan->update(['approval_status' => $request->status, 'approved_by' => auth()->user()->id]);
                } else if ($request->status == 'publish') {
                    $lesson_plan->update(['approval_status' => $request->status, 'approved_by' => auth()->user()->id]);
                } else if ($request->status == 'pending_for_approval') {
                    $lesson_plan->update(['approval_status' => $request->status]);
                }
            }


            if ($request->ajax()) {
                return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully', 'data' => new \stdClass()]);
            } else {
                return redirect()->back()->with('success', 'Status updated successfully');
            }
        } catch (QueryException $e) {
            return redirect()->back()->with('success', 'Something went wrong');
        }
    }

    public function taught_date(LessonPlan $lessonPlan)
    {

        $lessonPlan->load([
            'branch.class_group',
            'com_class',
            'subject',
            'sections',
        ]);
        $data['lessonPlan'] = $lessonPlan;

        $section_ids = array();
        if (isset($lessonPlan['subject_id']) && isset($lessonPlan['com_class_id'])) {
            $section_ids = BranchClassSection::whereHas('class_teachers', function ($query) use ($lessonPlan) {
                $query->where('subject_id', $lessonPlan['subject_id']);
            })->where('class_id', $lessonPlan['com_class_id'])->pluck('section_id')->toArray();
        }

        $data['sections'] = Section::whereIn('id', $section_ids)->get();
        $data['selected_section_ids'] = $lessonPlan->sections->pluck('id')->toArray();

        return view('lesson_plan.taught_date', $data);
    }

    public function list_attachments(LessonPlan $lessonPlan)
    {

        try {
            $data['lessonPlan'] = $lessonPlan->load(['attachments']);
            $returnData['attachments_rows'] = view('lesson_plan.attachments_rows', $data)->render();

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully', 'data' => $returnData]);
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function lessonPlanCalendar(Request $request)
    {

        if ($request->ajax()) {
            $data['events'] = array();
            $lesson_plans = LessonPlan::with([
                'term',
                'week',
                'com_class',
                'subject',
                'state',
            ])->whereNotNull(['taught_date_from', 'taught_date_to']);

            $lesson_plans = $this->filteration($request, $lesson_plans, 'calendar');

            $lesson_plans = $lesson_plans->get();

            $badge_color_classes = ['primary', 'success', 'warning', 'danger', 'dark', 'info'];

            foreach ($lesson_plans as $lesson_plan) {
                $event = array();
                $sections = $lesson_plan->sections()->pluck('section_name')->toArray();
                $event['title'] = $lesson_plan->topic . ' - ' . $lesson_plan['com_class']['class_name'] . ' - ' . $lesson_plan['subject']['subject_name'];
                $event['title'] = ! empty($sections) ? $event['title'] . ' - (' . implode('/', $sections) . ')' : $event['title'];
                $event['title'] = isset($lesson_plan['state']) ? $event['title'] . ' - ' . $lesson_plan['state']['state_name'] : $event['title'];
                $event['start'] = Carbon::parse($lesson_plan->taught_date_from)->format('Y-m-d');
                $event['end'] = Carbon::parse($lesson_plan->taught_date_to)->addDay()->format('Y-m-d');
                $event['allDay'] = false;
                // $event['className'] = 'bg-soft-'.$badge_color_classes[array_rand($badge_color_classes)];
                $event['className'] = 'bg-soft-dark';
                $event['url'] = route('lesson-plans.show', $lesson_plan->id);

                array_push($data['events'], $event);

                // Check for teacher absences on lesson plan dates
                $this->addTeacherAbsenceEvents($lesson_plan, $data['events']);
            }

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully.', 'data' => $data]);
        }

        $data = $this->filterationDropdownData();

        return view('lesson_plan.lesson_plan_calendar', $data);
    }

    public function duplicateLessonPlan($lesson_plan_id)
    {

        DB::beginTransaction();

        $lesson_plan = LessonPlan::with([
            'student_learning_outcomes.student_activities',
            'attachments'
        ])->where('id', $lesson_plan_id)->first();
        $lesson_plan['topic'] = $lesson_plan['topic'] . ' (Copy)';
        $lesson_plan['created_by'] = auth()->user()->id;
        $lesson_plan['approved_by'] = null;
        $lesson_plan['approval_status'] = null;
        $duplicate_lesson_plan = LessonPlan::create($lesson_plan->toArray());
        foreach ($lesson_plan['student_learning_outcomes'] as $student_learning_outcome) {
            $student_learning_outcome['lesson_plan_id'] = $duplicate_lesson_plan->id;
            $duplicate_SLO = StudentLearningOutcome::create($student_learning_outcome->toArray());
            foreach ($student_learning_outcome['student_activities'] as $student_activity) {
                $student_activity['student_learning_outcome_id'] = $duplicate_SLO->id;
                StudentActivity::create($student_activity->toArray());
            }
        }

        $file_names = Attachment::pluck('file_name')->unique()->toArray();

        foreach ($lesson_plan['attachments'] as $attachment) {
            $filename_data['filename'] = pathinfo($attachment['file_name'], PATHINFO_FILENAME);
            $filename_data['extension'] = pathinfo($attachment['file_name'], PATHINFO_EXTENSION);
            $filename_data['filenames_arr'] = $file_names;
            $filename = getUniqueFileName($filename_data);
            array_push($file_names, $filename);

            $duplicate_lesson_plan->attachments()->create([
                'file_name' => $filename,
                'file_type' => $attachment['file_type'],
                'user_id' => auth()->user()->id,
                'remarks' => $attachment['remarks'],
            ]);
            //dd($attachment['file_name'].', '.$filename);

            Storage::disk('s3')->copy('images/' . $attachment['file_name'], 'images/' . $filename);
        }

        DB::commit();

        return redirect()->route('lesson-plans.index')->with('success', 'Lesson Plan Duplicated Successfully.');
    }

    public function duplicateSelectedLessonPlan(Request $request)
    {
        $done = 0;
        $id = explode(",", $request->lesson_plans);
        foreach ($id as $lesson_plan_id) {
            DB::beginTransaction();

            $lesson_plan = LessonPlan::with([
                'student_learning_outcomes.student_activities',
                'attachments'
            ])->where('id', $lesson_plan_id)->first();
            $lesson_plan['topic'] = $lesson_plan['topic'] . ' (Copy)';
            $lesson_plan['created_by'] = auth()->user()->id;
            $lesson_plan['approved_by'] = null;
            $lesson_plan['approval_status'] = null;
            $duplicate_lesson_plan = LessonPlan::create($lesson_plan->toArray());
            foreach ($lesson_plan['student_learning_outcomes'] as $student_learning_outcome) {
                $student_learning_outcome['lesson_plan_id'] = $duplicate_lesson_plan->id;
                $duplicate_SLO = StudentLearningOutcome::create($student_learning_outcome->toArray());
                foreach ($student_learning_outcome['student_activities'] as $student_activity) {
                    $student_activity['student_learning_outcome_id'] = $duplicate_SLO->id;
                    StudentActivity::create($student_activity->toArray());
                }
            }

            $file_names = Attachment::pluck('file_name')->unique()->toArray();
            foreach ($lesson_plan['attachments'] as $attachment) {
                $filename_data['filename'] = pathinfo($attachment['file_name'], PATHINFO_FILENAME);
                $filename_data['extension'] = pathinfo($attachment['file_name'], PATHINFO_EXTENSION);
                $filename_data['filenames_arr'] = $file_names;
                $filename = getUniqueFileName($filename_data);
                array_push($file_names, $filename);

                $duplicate_lesson_plan->attachments()->create([
                    'file_name' => $filename,
                    'file_type' => $attachment['file_type'],
                    'user_id' => auth()->user()->id,
                    'remarks' => $attachment['remarks'],
                ]);

                Storage::disk('s3')->copy('images/' . $attachment['file_name'], 'images/' . $filename);
            }

            DB::commit();
            $done++;
        }
        if ($done) {
            return $done;
        } else {
            return '';
        }
    }


    public function filterationDropdownData()
    {

        $user = auth()->user();

        $data['academic_years'] = AcademicYear::all();
        if (auth()->user()->hasRole('network_associate')) {
            $data['branches'] = Branch::whereIn('id', auth()->user()['networkAssociates']['branches']->pluck('id')->toArray())->get();
        } elseif (! auth()->user()->hasRole('super_admin') && auth()->user()->hasPermission('add-lessonplan-taughtdate')) { //assuming, The user who has this permission would be TEACHER
            $data['branches'] = Branch::where('id', auth()->user()['employee']['branch_id'])->get();
        } else {
            $data['branches'] = Branch::all();
        }

        $data['classes'] = get_teacher_classes()->isNotEmpty() ? get_teacher_classes() : ComClass::all();
        $data['subjects'] = get_teacher_subjects()->isNotEmpty() ? get_teacher_subjects() : Subject::all();
        $data['terms'] = Term::all();
        $data['weeks'] = Week::all();
        $data['teachers'] = Employee::with(['user'])
            ->whereHas('designation', function ($query) {
                $query->whereIn('designation_name', ['Teacher']);
            });
        if (auth()->user()->hasRole('network_associate')) {
            $data['teachers'] = $data['teachers']->where('branch_id', get_set_NWABranchId());
        }

        $data['teachers'] = $data['teachers']->get();

        $data['states'] = State::all();
        if (! auth()->user()->hasRole('super_admin') && ! auth()->user()->hasPermission('approve-lesson-plan') && ! auth()->user()->hasRole('subject_coordinator')) {
            $branch_id = get_branch_id();
            $branch = Branch::with(['contact_information'])->where('id', $branch_id)->first();
            $state_id = isset($branch['contact_information']) ? $branch['contact_information']['state_id'] : 0;
            $data['states'] = State::where('id', $state_id)->get();
            $data['classes'] = get_branch_classes($branch_id);
        }

        return $data;
    }

    public function filteration($request, $query, $calling_from)
    {

        $user = auth()->user();

        if (get_teacher_classes()->isNotEmpty()) {
            $query = $query->whereIn('com_class_id', get_teacher_classes()->pluck('id')->toArray());
        }
        if (get_teacher_subjects()->isNotEmpty()) {
            $query = $query->whereIn('subject_id', get_teacher_subjects()->pluck('id')->toArray());
        }

        if (isset($request->academic_year_id)) {
            $query = $query->where('academic_year_id', $request->academic_year_id);
        }
        if (isset($request->branch_id)) {
            $query = $query->where(function ($query1) use ($request, $calling_from, $user) {
                $query1->where('branch_id', $request->branch_id);
                $query1->orWhereNull('branch_id');
                // if ($calling_from == 'index' && (auth()->user()->hasPermission('add-lessonplan-taughtdate') || $user->hasRole('network_associate')))
                if ($calling_from == 'index' && ! auth()->user()->hasPermission('approve-lesson-plan') && ! $user->hasRole('subject_coordinator') && ! $user->hasRole('super_admin')) {
                    $query1->where('approval_status', 'publish');
                }
            });
        } elseif ($calling_from == 'index' && ! auth()->user()->hasPermission('approve-lesson-plan') && ! $user->hasRole('subject_coordinator') && ! $user->hasRole('super_admin')) {
            $query->where('approval_status', 'publish');
        }

        if (isset($request->term_id)) {
            $query = $query->where('term_id', $request->term_id);
        }
        if (isset($request->com_class_id)) {
            $query = $query->where('com_class_id', $request->com_class_id);
        }
        if (isset($request->subject_id)) {
            $query = $query->where('subject_id', $request->subject_id);
        }
        if (isset($request->topic)) {
            $query = $query->where('topic', 'like', '%' . $request->topic . '%');
        }
        if (isset($request->state_id)) {
            $query = $query->where(function ($query1) use ($request) {
                $query1->where('state_id', $request->state_id);
                $query1->orWhereNull('state_id');
            });
        }
        if (isset($request->week_id)) {
            $query = $query->where('week_id', $request->week_id);
        }
        if (isset($request->created_date)) {
            $query = $query->whereDate('created_at', $request->created_date);
        }
        if (isset($request->from_date)) {
            $query = $query->where('taught_date_from', '>=', $request->from_date);
        }
        if (isset($request->to_date)) {
            $query = $query->where('taught_date_to', '<=', $request->to_date);
        }

        //Filteration for State
        /*if (!auth()->user()->hasRole('super_admin')){
            $state_id = 0;
            if (auth()->user()->hasRole('network_associate')){
                $branch_id = get_set_NWABranchId();
                $branch = Branch::with(['contact_information'])->where('id',$branch_id)->first();
                $state_id = isset($branch['contact_information']) ? $branch['contact_information']['state_id'] : 0;
            }else{
                $state_id = $user['employee'] ? $user['employee']['state_id'] : 0;
            }

            $query = $query->where(function ($query1)use($request,$state_id){
                $query1->where('state_id' , $state_id);
                $query1->orWhereNull('state_id');
            });
        }*/
        //Filteration for State END

        return $query;
    }


    public function test_email($queueable = 0)
    {

        $data = [
            'email' => ['muzammilnaeem1993@gmail.com'],
            'subject' => 'Test Mail',
            'message' => 'This is a test e-mail',
            'template' => 'email_templates.generic_email'
        ];

        //return view('email_templates.generic_email',$data);

        try {
            if ($queueable) {
                SendEmail::dispatch($data);
            } else {
                \Mail::send($data['template'], ['data' => $data], function ($message) {
                    $message->to('muzammilnaeem1993@gmail.com');
                    $message->subject('Testing Email');
                });
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return 'Email sent';
    }

    /**
     * Add teacher absence events to calendar
     *
     * @param LessonPlan $lessonPlan
     * @param array $events
     * @return void
     */
    private function addTeacherAbsenceEvents($lessonPlan, &$events)
    {
        // Find teachers assigned to this lesson plan's class and subject
        $teachers = ClassTeacher::with(['employee.user'])
            ->where('academic_year_id', $lessonPlan->academic_year_id)
            ->where('subject_id', $lessonPlan->subject_id)
            ->whereHas('branch_class_section', function ($query) use ($lessonPlan) {
                $query->where('class_id', $lessonPlan->com_class_id);
            })
            ->get();

        if ($teachers->isEmpty()) {
            return;
        }

        $lessonStartDate = Carbon::parse($lessonPlan->taught_date_from);
        $lessonEndDate = Carbon::parse($lessonPlan->taught_date_to);

        foreach ($teachers as $classTeacher) {
            $teacher = $classTeacher->employee;
            if (! $teacher) {
                continue;
            }

            // Check for approved leave applications
            $leaveApplications = LeaveApplication::where('employee_id', $teacher->id)
                ->where('status', 1) // Approved leaves
                ->where(function ($query) use ($lessonStartDate, $lessonEndDate) {
                    $query->whereBetween('from_date', [$lessonStartDate->format('Y-m-d'), $lessonEndDate->format('Y-m-d')])
                          ->orWhereBetween('to_date', [$lessonStartDate->format('Y-m-d'), $lessonEndDate->format('Y-m-d')])
                          ->orWhere(function ($q) use ($lessonStartDate, $lessonEndDate) {
                              $q->where('from_date', '<=', $lessonStartDate->format('Y-m-d'))
                                ->where('to_date', '>=', $lessonEndDate->format('Y-m-d'));
                          });
                })
                ->get();

            foreach ($leaveApplications as $leave) {
                $absenceStart = Carbon::parse($leave->from_date);
                $absenceEnd = Carbon::parse($leave->to_date);

                // Create absence event
                $absenceEvent = [
                    'title' => '🚫 Teacher Absent: ' . $teacher->user->first_name . ' ' . $teacher->user->last_name,
                    'start' => $absenceStart->format('Y-m-d'),
                    'end' => $absenceEnd->addDay()->format('Y-m-d'),
                    'allDay' => true,
                    'className' => 'bg-soft-danger teacher-absence',
                    'url' => '#',
                    'extendedProps' => [
                        'type' => 'teacher_absence',
                        'teacher_name' => $teacher->user->first_name . ' ' . $teacher->user->last_name,
                        'leave_type' => $leave->leaveType->name ?? 'Leave',
                        'reason' => $leave->reason ?? 'No reason provided'
                    ]
                ];

                $events[] = $absenceEvent;
            }

            // Check for unmarked attendance (potential absence)
            $unmarkedDates = EmployeeAttendance::where('employee_id', $teacher->id)
                ->whereBetween('created_at', [$lessonStartDate->format('Y-m-d'), $lessonEndDate->format('Y-m-d')])
                ->whereNull('time_in')
                ->get();

            foreach ($unmarkedDates as $unmarked) {
                $unmarkedDate = Carbon::parse($unmarked->created_at);

                $unmarkedEvent = [
                    'title' => '⚠️ Unmarked Attendance: ' . $teacher->user->first_name . ' ' . $teacher->user->last_name,
                    'start' => $unmarkedDate->format('Y-m-d'),
                    'end' => $unmarkedDate->addDay()->format('Y-m-d'),
                    'allDay' => true,
                    'className' => 'bg-soft-warning teacher-unmarked',
                    'url' => '#',
                    'extendedProps' => [
                        'type' => 'teacher_unmarked',
                        'teacher_name' => $teacher->user->first_name . ' ' . $teacher->user->last_name,
                        'reason' => 'Attendance not marked'
                    ]
                ];

                $events[] = $unmarkedEvent;
            }
        }
    }
}
