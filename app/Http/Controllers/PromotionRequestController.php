<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\BranchClass;
use App\Models\BranchClassSection;
use App\Models\ClassStudent;
use App\Models\ComClass;
use App\Models\FeePackage;
use App\Models\GradeBookHistory;
use App\Models\PromotionRequest;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentBehaviourSkill;
use App\Models\StudentFeePackage;
use App\Models\StudentInvoice;
use App\Models\StudentPromotionRequest;
use App\Models\StudentLedger;
use App\Models\StudentLedgerInvoice;
use App\Models\BranchAcademicYear;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class PromotionRequestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            // Start query with eager loading to avoid N+1 problem
            $query = PromotionRequest::with([
                'prev_branch',
                'prev_class',
                'prev_section',
                'prev_academic_year',
                'cur_branch',
                'cur_class',
                'cur_section',
                'cur_academic_year',
                'student_promotion_requests.student'
            ])->orderBy('created_at', 'desc');

            // Role-based filtering
            if (!isSuperAdmin() && !isHeadOfficeEmp()) {
                $branchId = get_branch_id();
                $query->where(function ($q) use ($branchId) {
                    $q->where('prev_branch_id', $branchId)
                        ->orWhere('cur_branch_id', $branchId);
                });
            }

            // Apply filters
            if ($request->filled('prev_academic_year')) {
                $query->where('prev_academic_year_id', $request->prev_academic_year);
            }

            if ($request->filled('cur_academic_year')) {
                $query->where('cur_academic_year_id', $request->cur_academic_year);
            }

            if ($request->filled('from_branch_id')) {
                $query->where('prev_branch_id', $request->from_branch_id);
            }

            if ($request->filled('from_class_id')) {
                $query->where('prev_class_id', $request->from_class_id);
            }

            if ($request->filled('to_branch_id')) {
                $query->where('cur_branch_id', $request->to_branch_id);
            }

            if ($request->filled('to_class_id')) {
                $query->where('cur_class_id', $request->to_class_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('is_promotion')) {
                $query->where('is_promotion', $request->is_promotion);
            }

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('pre_aca', fn($row) => $row->prev_academic_year->title ?? '')
                ->addColumn('pre_branch', fn($row) => $row->prev_branch ? "{$row->prev_branch->br_name} ({$row->prev_branch->branch_code})" : '')
                ->addColumn('pre_class', fn($row) => $row->prev_class->class_name ?? '')
                ->addColumn('pre_sec', fn($row) => $row->prev_section->section_name ?? '')
                ->addColumn('cur_aca', fn($row) => $row->cur_academic_year->title ?? '')
                ->addColumn('cur_branch', fn($row) => $row->cur_branch ? "{$row->cur_branch->br_name} ({$row->cur_branch->branch_code})" : '')
                ->addColumn('cur_class', fn($row) => $row->cur_class->class_name ?? '')
                ->addColumn('cur_sec', fn($row) => $row->cur_section->section_name ?? '')
                ->addColumn('action', fn($row) => view('promotion_requests.actions', ['row' => $row])->render())
                ->rawColumns(['action'])
                ->make(true);
        }

        // Handle non-AJAX requests (normal view load)
        if (!isSuperAdmin() && !isHeadOfficeEmp()) {
            $branchId = get_branch_id();
            $branches = Branch::where('id', $branchId)->get();
            $classes = BranchClass::where('branch_id', $branchId)->with('com_classes')->get();
        } else {
            $branches = Branch::all();
            $classes = ComClass::all();
        }

        $sections = Section::all();
        $academic_years = AcademicYear::all();

        return view('promotion_requests.promotion_request', [
            'classes' => $classes,
            'sections' => $sections,
            'branches' => $branches,
            'academic_years' => $academic_years,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!isSuperAdmin() && !isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $branches = Branch::where('id', $branch_id)->get();
        } else {
            $branches = Branch::all();
        }
        $academic_years = AcademicYear::all();
        $academic_year_latest = AcademicYear::latest()->first();
        return view('promotion_requests.create_promotion_request', ['branches' => $branches, 'academic_years' => $academic_years, 'academic_year_latest' => $academic_year_latest]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validateRequest($request)->validate();
        // if(isset($request->last_unpaid_amount) && !empty($request->last_unpaid_amount) && $request->last_unpaid_amount > 0){
        //     return redirect()->back()->with('error', 'Student has last unpaid amount');
        // }

        if ($this->isAlreadyPromoted([$request->student_id])) {
            return redirect()->back()->with('error', 'Student Promotion Request is already initiated!');
        }

        if ($this->isNotOnRoll($request->student_id)) {
            return redirect()->back()->with('error', 'Student is not on roll!');
        }

        if ($request->is_promotion && !$this->validateClassProgression($request->class_id, $request->promoted_branch_class_id)) {
            return redirect()->back()->with('error', 'Invalid class progression! Student can only be promoted to the next higher class (sort order + 1).');
        }

        // if(isset($request->last_unpaid_amount) && !empty($request->last_unpaid_amount) && $request->last_unpaid_amount > 0){
        //     return redirect()->back()->with('error', 'Student has last unpaid amount');
        // }
        try {

            $promoted_class_id = null;
            $cur_branch_class_section_id = null;

            if ($request->is_promotion && $request->academic_year == $request->promoted_academic_year_id) {
                return redirect()->back()->with('error', 'Please select higher promoted academic year');
            } else if (!$request->is_promotion && $request->academic_year != $request->promoted_academic_year_id) {
                return redirect()->back()->with('error', 'Please select promoted academic year same as previous');
            }

            if ($request->is_promotion) {
                $promoted_class_id = BranchClass::where('id', '=', $request->promoted_branch_class_id)->first()->class_id;
                $cur_branch_class_section_id = BranchClassSection::where('branch_id', '=', $request->promoted_branch_id)
                    ->where('class_id', '=', $promoted_class_id)
                    ->where('section_id', '=', $request->promoted_section_id)->first()->id;
            }

            $promotion_request = PromotionRequest::create([
                'prev_branch_id' => $request->branch_id,
                'prev_class_id' => $request->class_id,
                'prev_section_id' => $request->section_id,
                'prev_branch_class_section_id' => $request->branch_class_section_id,
                'prev_academic_year_id' => $request->academic_year,
                'cur_branch_id' => $request->promoted_branch_id,
                'cur_class_id' => $promoted_class_id,
                'cur_section_id' => $request->promoted_section_id,
                'cur_branch_class_section_id' => $cur_branch_class_section_id,
                'cur_academic_year_id' => $request->promoted_academic_year_id,
                'created_by' => Auth::user()->id,
                'type' => $request->promotion_type,
                'is_promotion' => $request->is_promotion,   /*0 => pass-out , 1 => promoted*/
                'status' => 'PENDING'
            ]);

            $student_promotion_request = $promotion_request->student_promotion_request()->create([
                'student_id' => $request->student_id,
            ]);

            if ($student_promotion_request) {
                return redirect()->back()->with('success', 'Student promotion request is initiated successfully!');
            }
            return redirect()->back()->with('error', 'Oops! Something went wrong');
        } catch (\Exception $e) {
            abort(500, );
        }
    }

    public function bulkStore(Request $request)
    {
        $this->bulkValidate($request)->validate();

        $students_ids = explode(',', $request->student_ids[0]);
        if ($this->isAlreadyPromoted($students_ids)) {
            return redirect()->back()->with('error', 'Student Promotion Request is already initiated!');
        }

        if ($request->is_promotion && !$this->validateClassProgression($request->class_id, $request->promoted_branch_class_id)) {
            return redirect()->back()->with('error', 'Invalid class progression! Student can only be promoted to the next higher class (sort order + 1).');
        }
        try {
            if (count($request->student_ids) == 0) {
                return redirect()->back()->with('error', 'Please select at least one student!');
            }

            if ($request->is_promotion && $request->academic_year_id == $request->promoted_academic_year_id) {
                return redirect()->back()->with('error', 'Please select higher promoted academic year');
            } else if (!$request->is_promotion && $request->academic_year_id != $request->promoted_academic_year_id) {
                return redirect()->back()->with('error', 'Please select promoted academic year same as previous');
            }
            /*
             * class_id is branch_class_id
             * section_id is branch_class_section_id
             * */
            $prev_class_id = BranchClass::where('id', '=', $request->class_id)->first()->class_id;
            $prev_section_id = BranchClassSection::where('id', '=', $request->section_id)->first()->section_id;
            $promoted_class_id = null;
            $promoted_section_id = null;
            $promoted_branch_class_section_id = null;

            if ($request->is_promotion) {
                $promoted_class_id = BranchClass::where('id', '=', $request->promoted_branch_class_id)->first()->class_id;
                $promoted_section_id = $request->promoted_section_id;
                $promoted_branch_class_section_id = BranchClassSection::where('branch_id', '=', $request->promoted_branch_id)
                    ->where('class_id', '=', $promoted_class_id)
                    ->where('section_id', '=', $request->promoted_section_id)->first()->id;
            }

            $promotion_request = PromotionRequest::create([
                'prev_branch_id' => $request->branch_id,
                'prev_class_id' => $prev_class_id,
                'prev_section_id' => $prev_section_id,
                'prev_branch_class_section_id' => $request->section_id,
                'prev_academic_year_id' => $request->academic_year_id,
                'cur_branch_id' => $request->promoted_branch_id,
                'cur_class_id' => $promoted_class_id,
                'cur_section_id' => $promoted_section_id,
                'cur_branch_class_section_id' => $promoted_branch_class_section_id,
                'cur_academic_year_id' => $request->promoted_academic_year_id,
                'created_by' => Auth::user()->id,
                'type' => $request->promotion_type,
                'is_promotion' => $request->is_promotion,   /*0 => pass-out , 1 => promoted*/
                'status' => "PENDING"
            ]);

            foreach ($students_ids as $student_id) {
                $promotion_request->student_promotion_request()->create([
                    'student_id' => (int) $student_id,
                ]);
            }
            return redirect()->back()->with('success', 'Student promotion request is initiated successfully!');
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function bulkUpdate(Request $request)
    {
        $this->bulkUpdateValidate($request)->validate();
        try {
            if (count($request->student_ids) == 0) {
                return redirect()->back()->with('error', 'Please select at least one student!');
            }

            if ($request->is_promotion && $request->academic_year_id == $request->promoted_academic_year_id) {
                return redirect()->back()->with('error', 'Please select higher promoted academic year');
            } else if (!$request->is_promotion && $request->academic_year_id != $request->promoted_academic_year_id) {
                return redirect()->back()->with('error', 'Please select promoted academic year same as previous');
            }
            /*
             * class_id is branch_class_id
             * section_id is branch_class_section_id
             * */
            $promotion_request = PromotionRequest::where('id', '=', $request->promotion_request_id);

            $promoted_class_id = $promotion_request->first()->cur_class_id;
            $promoted_section_id = $promotion_request->first()->cur_section_id;
            $promoted_branch_class_section_id = $promotion_request->first()->cur_branch_class_section_id;

            if ($request->is_promotion) {
                $promoted_class_id = BranchClass::where('id', '=', $request->promoted_branch_class_id)->first()->class_id;
                $promoted_section_id = $request->promoted_section_id;
                $promoted_branch_class_section_id = BranchClassSection::where('branch_id', '=', $request->promoted_branch_id)
                    ->where('class_id', '=', $promoted_class_id)
                    ->where('section_id', '=', $request->promoted_section_id)->first()->id;
            }

            $promotion_request->update([
                'cur_branch_id' => $request->promoted_branch_id,
                'cur_class_id' => $promoted_class_id,
                'cur_section_id' => $promoted_section_id,
                'cur_branch_class_section_id' => $promoted_branch_class_section_id,
                'cur_academic_year_id' => $request->promoted_academic_year_id,
                'created_by' => Auth::user()->id,
                'is_promotion' => $request->is_promotion,   /*0 => pass-out , 1 => promoted*/
            ]);

            $students_ids = explode(',', $request->student_ids[0]);
            foreach ($students_ids as $student_id) {
                $promotion_request->first()->student_promotion_request()->where('student_id', '=', $student_id)->update([
                    'student_id' => (int) $student_id,
                ]);
            }
            return redirect()->route('promotion-requests.index')->with('success', 'Student promotion request is updated successfully!');
        } catch (Exception $e) {
            abort(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\PromotionRequest $promotionRequest
     * @return \Illuminate\Http\Response
     */
    public function show(PromotionRequest $promotionRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\PromotionRequest $promotionRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(PromotionRequest $promotionRequest)
    {
        $academic_years = AcademicYear::all(['id', 'title']);
        $branches = Branch::all();
        $promotionRequest = $promotionRequest->load([
            'prev_branch',
            'prev_class',
            'prev_section',
            'prev_branch_class_section',
            'prev_academic_year',
            'cur_branch',
            'cur_branch.branch_class_section.com_classes',
            'cur_branch.branch_class_section.sections',
            'cur_class',
            'cur_section',
            'cur_branch_class_section',
            'cur_academic_year',
            'student_promotion_request'
        ]);

        $branch_classes = BranchClass::where('branch_id', '=', $promotionRequest->cur_branch_id)->with('com_classes')->get();

        $class_sections = $promotionRequest->cur_branch->branch_class_section->where('branch_id', '=', $promotionRequest->cur_branch_id)
            ->where('class_id', '=', $promotionRequest->cur_class_id);

        if ($promotionRequest->type == 'bulk') {
            $prev_branch_classes = BranchClass::where('branch_id', '=', $promotionRequest->prev_branch_id)->with('com_classes')->get();
            $prev_class_sections = $promotionRequest->prev_branch->branch_class_section->where('branch_id', '=', $promotionRequest->prev_branch_id)
                ->where('class_id', '=', $promotionRequest->prev_class_id);

            return view('promotion_requests.create_promotion_request', [
                'academic_years' => $academic_years,
                'branches' => $branches,
                'branch_classes' => $branch_classes,
                'class_sections' => $class_sections,
                'prev_branch_classes' => $prev_branch_classes,
                'prev_class_sections' => $prev_class_sections,
                'promotionRequest' => $promotionRequest
            ]);
        }

        return view('promotion_requests.individual_promotion_request', [
            'academic_years' => $academic_years,
            'branches' => $branches,
            'branch_classes' => $branch_classes,
            'class_sections' => $class_sections,
            'promotionRequest' => $promotionRequest
        ]);
    }

    public function bulkEdit($promotion_request_id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PromotionRequest $promotionRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PromotionRequest $promotionRequest)
    {
        $this->validateRequest($request)->validate();
        try {
            $promoted_class_id = BranchClass::where('id', '=', $request->promoted_branch_class_id)->first()->class_id;
            $promoted_branch_class_section_id = BranchClassSection::where('branch_id', '=', $request->promoted_branch_id)
                ->where('class_id', '=', $promoted_class_id)
                ->where('section_id', '=', $request->promoted_section_id)->first()->id;

            $updatedPromotion = $promotionRequest->update([
                'cur_academic_year_id' => $request->promoted_academic_year_id,
                'cur_branch_id' => $request->promoted_branch_id,
                'cur_class_id' => $promoted_class_id,
                'cur_section_id' => $request->promoted_section_id,
                'cur_branch_class_section_id' => $promoted_branch_class_section_id,
                'is_promotion' => $request->is_promotion,
            ]);

            if ($updatedPromotion) {
                return redirect()->route('promotion-requests.index')->with('success', 'Student promotion request is updated successfully!');
            }
            return redirect()->back()->with('error', 'Oops! Something went wrong');
        } catch (\Exception $e) {
            abort('500');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PromotionRequest $promotionRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromotionRequest $promotionRequest)
    {
        try {
            $promotionRequest->student_promotion_requests->each->delete();
            return $promotionRequest->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function listStudents(Request $request)
    {
        if (isset($request->sections)) {
            $student_ids = ClassStudent::where('is_valid', 1)->whereIn('branch_class_section_id', $request->sections)
                //Remove left students
                ->whereHas('students', function ($query) {
                    $query->where('status', '!=', 'left');
                })
                ->get()->pluck('students.id');
            // dd($list->toArray());
        } else if (!isset($request->sections) && isset($request->classes)) {
            $student_ids = ClassStudent::where('is_valid', 1)->whereHas('branch_class_sections', function ($query) use ($request) {
                $query->whereHas('com_classes', function ($subquery) use ($request) {
                    $subquery->whereIn('branch_id', $request->branches)->whereIn('class_id', $request->classes);
                });
            })
                //Remove left students
                ->whereHas('students', function ($query) {
                    $query->where('status', '!=', 'left');
                })
                ->get()->pluck('students.id');
        } else {
            $student_ids = ClassStudent::where('is_valid', 1)->whereHas('branch_class_sections', function ($query) use ($request) {
                $query->whereHas('com_classes', function ($subquery) use ($request) {
                    $subquery->whereIn('branch_id', $request->branches);
                });
            })  //Remove left students
                ->whereHas('students', function ($query) {
                    $query->where('status', '!=', 'left');
                })
                ->get()->pluck('students.id');
        }
        // dd($student_ids);
        $students = [];
        //dd($student_ids);

        foreach ($student_ids as $student_id) {
            if (!empty($student_id)) {
                $student_active_class = Student::where('id', $student_id)->with('active_class.branch_class_sections')->first();
                if ($student_active_class) {
                    $student = Student::where('id', $student_id)->whereHas('student_invoices', function ($query) use ($student_active_class) {
                        $query->where(['is_paid' => 1, 'invoice_frequency' => 'Admission'])->whereHas('student_fee_package', function ($query) use ($student_active_class) {
                            $query->where([
                                'academic_year_id' => $student_active_class->active_class->academic_year_id,
                                'com_class_id' => $student_active_class->active_class->branch_class_sections->com_classes->id,
                                'section_id' => $student_active_class->active_class->branch_class_sections->sections->id,
                            ]);
                        });
                    })->with(['active_class.branch_class_sections.sections', 'active_class.branch_class_sections.com_classes'])->first();
                }

                if (isset($student))
                    $students = array_merge($students, [$student->toArray()]);
            }
        }

        return DataTables::of($students)
            ->addIndexColumn()
            ->addColumn('full_name', function ($row) {
                return view('students.student_image_tr', ['row' => $row]);
            })
            ->addColumn('invoice_status', function ($row) use ($request) {
                $checkInvoice = StudentInvoice::where(['student_id' => $row['id'], 'fee_period_id' => $request['filters']['feePeriodInput']])->where('bank_payment_status', '!=', 'cancelled')->first();
                if (isset($checkInvoice))
                    return '<span class="badge bg-danger">Already Generated</span>';
                return '<span class="badge bg-primary">No Invoice</span>';
            })
            ->addColumn('action', function ($row) use ($request) {
                $check_disable = TRUE;
                $checkInvoice = StudentInvoice::where(['student_id' => $row['id'], 'fee_period_id' => $request['filters']['feePeriodInput']])->where('bank_payment_status', '!=', 'cancelled')->first();
                if (isset($checkInvoice))
                    $check_disable = FALSE;

                return view('students.bulk_invoices.bulk_students_action', ['row' => $row, 'check_disable', $check_disable]);
            })
            ->rawColumns(['full_name', 'invoice_status', 'action'])
            ->make(TRUE);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function showStudentsList(Request $request)
    {
        /*
         * sections is branch_class_section_did
         * */
        $student_ids = array();
        if (isset($request->academic_years) && isset($request->branches) && isset($request->classes) && isset($request->sections)) {
            $student_ids = ClassStudent::where('is_valid', 1);

            if (!is_null($request->students)) {
                $student_ids = $student_ids->whereIn('student_id', $request->students);
            }

            $student_ids = $student_ids->whereIn('branch_class_section_id', $request->sections)
                ->whereHas('academic_years', function ($q) use ($request) {
                    $q->whereIn('academic_year_id', $request->academic_years);
                })
                //Remove left students
                ->whereHas('students', function ($query) {
                    $query->where('status', '!=', 'left')
                        ->where('status', '!=', 'registered');
                })
                ->get()->pluck('students.id');
        }/*else if ( isset($request->academic_years) && isset($request->branches) && isset($request->classes) ) {
           $student_ids = ClassStudent::where('is_valid', 1)
               ->whereHas('academic_years', function($q) use ($request){
                   $q->whereIn('academic_year_id', $request->academic_years);
               })
               ->whereHas('branch_class_sections', function ($query) use ($request){
                   $query->whereHas('branches', function ($subquery) use ($request){
                       $subquery->whereIn('branch_id', $request->branches);
                   });
               })
               ->whereHas('branch_class_sections', function ($query) use ($request) {
                   $query->whereIn('class_id', $request->classes);
               })
               //Remove left students
               ->whereHas('students', function ($query) {
                   $query->where('status', '!=', 'left');
               })
               ->get()->pluck('students.id');
       }*/ else if (isset($request->academic_years) && isset($request->branches)) {
            $student_ids = ClassStudent::where('is_valid', 1)
                ->whereHas('academic_years', function ($q) use ($request) {
                    $q->whereIn('academic_year_id', $request->academic_years);
                })
                ->whereHas('branch_class_sections', function ($query) use ($request) {
                    $query->whereHas('com_classes', function ($subquery) use ($request) {
                        $subquery->whereIn('branch_id', $request->branches);
                    });
                })
                //Remove left students
                ->whereHas('students', function ($query) {
                    $query->where('status', '!=', 'left');
                })
                ->get()->pluck('students.id');
        }
        $students = [];

        foreach ($student_ids as $student_id) {
            if (!empty($student_id)) {
                $student_active_class = Student::where('id', $student_id)->with('active_class.branch_class_sections')->first();
                if ($student_active_class) {
                    /* dd( Student::where('id', $student_id)->whereHas('student_invoices', function ($q) use ($student_active_class){
                         $q->where(['is_paid' => 1, 'invoice_frequency' => 'Admission'])->whereHas('student_fee_package', function ($q) use ($student_active_class){
                             $q->where([
                                // 'academic_year_id' => $student_active_class->active_class->academic_year_id,
                                // 'com_class_id' => $student_active_class->active_class->branch_class_sections->com_classes->id,
                                 'section_id' => $student_active_class->active_class->branch_class_sections->sections->id
                             ]);
                         });
                     })->first(),  $student_active_class->active_class->academic_year_id, $student_active_class->active_class->branch_class_sections->com_classes->id,$student_active_class->active_class->branch_class_sections->sections->id, StudentFeePackage::where(['student_id' => $student_id, 'academic_year_id' => $student_active_class->active_class->academic_year_id, 'com_class_id' => $student_active_class->active_class->branch_class_sections->com_classes->id, 'section_id' => $student_active_class->active_class->branch_class_sections->sections->id ])->get() );*/
                    //  $student = StudentInvoice::where(['student_id'=> $student_id, 'is_paid' => '0'])->get();
                    //  if(count($student) > 0){
                    //      continue;
                    //  }
                    $student = Student::where('id', $student_id)->whereHas('student_invoices', function ($query) use ($student_active_class) {
                        $query->where(['is_paid' => 1, 'invoice_frequency' => 'Admission'])->whereHas('student_fee_package', function ($query) use ($student_active_class) {
                            $query->where([
                                'academic_year_id' => $student_active_class->active_class->academic_year_id,
                                'com_class_id' => $student_active_class->active_class->branch_class_sections->com_classes->id,
                                'section_id' => $student_active_class->active_class->branch_class_sections->sections->id,
                            ]);
                        });
                    })->with(['active_class.branch_class_sections.sections', 'active_class.branch_class_sections.com_classes'])->first();
                }

                if (isset($student))
                    $students = array_merge($students, [$student->toArray()]);
            }
        }
        return DataTables::of($students)
            ->addIndexColumn()
            ->addColumn('invoice_no', function ($row) use ($request) {
                $checkInvoice = StudentInvoice::select('id', 'student_id', 'invoice_no', 'invoice_frequency', 'created_at')->where(['student_id' => $row['id']])->where('invoice_frequency', '=', 'Monthly')->latest()->first();
                $invoice_no = $checkInvoice->invoice_no ?? '';
                return '<td>' . $invoice_no . '</td>';
            })
            ->addColumn('invoice_status', function ($row) use ($request) {
                $checkInvoice = StudentInvoice::select('id', 'student_id', 'bank_payment_status', 'invoice_frequency', 'created_at')->where(['student_id' => $row['id']])->where('invoice_frequency', '=', 'Monthly')->latest()->first();
                $bank_payment_status = $checkInvoice->bank_payment_status ?? '';
                if (isset($checkInvoice))
                    return '<span class="badge bg-info">' . $bank_payment_status . '</span>';
                return '<span class="badge bg-primary">No Invoice</span>';
            })
            ->addColumn('branch_name', function ($row) {
                $branchInfo = Branch::where('id', $row['branch_id'])->first();
                return $branchInfo->br_name;
            })
            ->addColumn('full_name', function ($row) {
                return view('students.student_image_tr', ['row' => $row]);
            })
            ->addColumn('promotion_status', function ($row) {
                $promotion_status = '';
                $student_promotion_request = StudentPromotionRequest::where('student_id', $row['id'])->first();
                if (isset($student_promotion_request)) {
                    if ($student_promotion_request->promotion_request->status == "PENDING") {
                        $promotion_status = '<span class="badge bg-warning">Pending</span>';
                    } else if ($student_promotion_request->promotion_request->status == "APPROVED") {
                        $promotion_status = '<span class="badge bg-success">Approved</span>';
                    } else if ($student_promotion_request->promotion_request->status == "REJECTED") {
                        $promotion_status = '<span class="badge bg-danger">Rejected</span>';
                    }
                } else {
                    $promotion_status = '<span class="badge bg-success">No Request</span>';
                }
                return $promotion_status;
            })
            ->addColumn('action', function ($row) use ($request) {
                $check_disable = FALSE;
                $checkInvoice = StudentInvoice::select('id', 'student_id', 'bank_payment_status', 'invoice_frequency', 'created_at')->where(['student_id' => $row['id']])->where('invoice_frequency', '=', 'Monthly')->latest()->first();
                /*if (isset($checkInvoice) && $checkInvoice->bank_payment_status == "paid") $check_disable = true;*/
                $checked = $request->edit ? 'checked' : '';
                return view('students.bulk_invoices.bulk_students_action', ['row' => $row/*, 'check_disable' => $check_disable*/]);
            })
            ->rawColumns(['invoice_no', 'full_name', 'invoice_status', 'promotion_status', 'action'])
            ->make(TRUE);
    }

    /**
     * @param $promotion_request_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPromotionRequest($promotion_request_id): \Illuminate\Http\JsonResponse
    {
        $student_promotion_requests = StudentPromotionRequest::with([
            'student',
            'promotion_request.prev_branch',
            'promotion_request.prev_class',
            'promotion_request.prev_section',
            'promotion_request.prev_academic_year',
            'promotion_request.cur_branch',
            'promotion_request.cur_class',
            'promotion_request.cur_section',
            'promotion_request.cur_academic_year',
        ])->where('promotion_request_id', '=', $promotion_request_id)->get();

        $html = view('promotion_requests.promotion_requests_modal', ['student_promotion_requests' => $student_promotion_requests])->render();

        return response()->json(array('html' => $html));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilters(Request $request)
    {
        $branch = Branch::where('id', '=', $request->branches[0])->select('id', 'br_name', 'branch_code')->first();
        $academic_years = AcademicYear::all(['id', 'title']);
        $branch_classes = BranchClass::with([
            'com_classes' => function ($q) {
                $q->select('id', 'class_name');
            }
        ])->where('branch_id', '=', $branch->id)->get();
        $branch_class_sections = BranchClassSection::with([
            'sections' => function ($q) {
                $q->select('id', 'section_name');
            }
        ])
            ->where('branch_id', '=', $branch->id)
            ->get();

        $data['html'] = view('promotion_requests.promoted_filters', [
            'promotion' => TRUE,
            'branch' => $branch,
            'academic_years' => $academic_years,
            'branch_classes' => $branch_classes,
            'branch_class_sections' => $branch_class_sections,
            'selected_class_id' => $request->classes[0],
            'selected_section_id' => $request->sections[0],
            'selected_branch_id' => $request->branches[0],
            'selected_academic_year_id' => $request->academic_years[0],
        ])->render();

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showIndividualPromotion(Request $request)
    {
        $academic_years = AcademicYear::all(['id', 'title']);
        if (!isSuperAdmin() && !isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $branches = Branch::where('id', $branch_id)->get();
        } else {
            $branches = Branch::all();
        }
        return view('promotion_requests.individual_promotion_request', ['academic_years' => $academic_years, 'branches' => $branches]);
    }


    /**
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateRequest($request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'student_roll_no' => 'required',
            'promotion_type' => 'required',
            'academic_year' => 'required',
            'student_id' => 'required',
            'branch_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'branch_class_section_id' => 'required',
            'promoted_academic_year_id' => 'required',
            'promoted_branch_id' => 'required',
            'is_promotion' => 'required|boolean',
            'promoted_branch_class_id' => 'required_if:is_promotion,true',
            'promoted_section_id' => 'required_if:is_promotion,true',
        ], [
            'student_roll_no.required' => 'Student ID is required',
            'promoted_academic_year_id.required' => 'Academic Year is required',
            'promoted_branch_id.required' => 'Branch is required',
            'promoted_branch_class_id.required' => 'Class is required',
            'promoted_section_id.required' => 'Section is required',
        ]);
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function bulkValidate($request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'academic_year_id' => 'required',
            'branch_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'student_ids.*' => 'required',
            'promoted_academic_year_id' => 'required',
            'promoted_branch_id' => 'required',
            'is_promotion' => 'required|boolean',
            'promoted_branch_class_id' => 'required_if:is_promotion,true',
            'promoted_section_id' => 'required_if:is_promotion,true',
        ], [
            'student_ids.*.required' => 'Please select at least one student!',
            'academic_year_id.required' => 'Academic Year is required',
            'branch_id.required' => 'Branch is required',
            'class_id.required' => 'Class is required',
            'section_id.required' => 'Section is required',
            'student_ids.required' => 'Student IDs are required',
            'promoted_academic_year_id.required' => 'Academic Year is required',
            'promoted_branch_id.required' => 'Branch is required',
            'promoted_branch_class_id.required' => 'Class is required',
            'promoted_section_id.required' => 'Section is required',
        ]);
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function bulkUpdateValidate($request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'is_promotion' => 'required|boolean',
            'student_ids.*' => 'required',
            'promoted_academic_year_id' => 'required',
            'promoted_branch_id' => 'required',
            'promoted_branch_class_id' => 'required_if:is_promotion,true',
            'promoted_section_id' => 'required_if:is_promotion,true',
        ], [
            'is_promotion.required' => 'Promotion type is required',
            'student_ids.*.required' => 'Please select at least one student!',
            'student_ids.required' => 'Student IDs are required',
            'promoted_academic_year_id.required' => 'Academic Year is required',
            'promoted_branch_id.required' => 'Branch is required',
            'promoted_branch_class_id.required' => 'Class is required',
            'promoted_section_id.required' => 'Section is required',
        ]);
    }

    /**
     * @param $student_id
     * @return bool
     */
    public function isAlreadyPromoted($student_id): bool
    {
        $result = StudentPromotionRequest::with('promotion_request')->whereIn('student_id', $student_id)->first();
        return isset($result) ? $result->promotion_request->status == 'PENDING' : false;
    }

    /**
     * @param $student_id
     * @return bool
     */
    public function isNotOnRoll($student_id): bool
    {
        $result = Student::where('id', '=', $student_id)->first();
        return $result->status == 'left' || $result->status == 'registered';
    }

    /**
     * @param $student_id
     * @return bool
     */
    public function isRegistered($student_id): bool
    {
        $result = Student::where('id', '=', $student_id)->first();
        return $result->status == 'registered';
    }

    public function createRequestRejection(Request $request)
    {
        $id = $request->id;
        $promoRequest = PromotionRequest::where('id', $id)->with([
            'prev_branch',
            'prev_class',
            'prev_section',
            'prev_academic_year',
            'cur_branch',
            'cur_class',
            'cur_section',
            'cur_academic_year',
            'student_promotion_requests.student'
        ])->first();


        return view('promotion_requests.request_reject_modal', [
            'promoRequest' => $promoRequest
        ]);
    }

    public function storeRequestRejection(Request $request)
    {

        $requestArray = $request->validate([
            "record_id" => "required",
            "rejected_by" => "required",
            "rejected_date" => "required",
            "rejection_remarks" => "required"
        ]);

        $promoRequest = PromotionRequest::where('id', $request->record_id)->with([
            'prev_branch',
            'prev_class',
            'prev_section',
            'prev_academic_year',
            'cur_branch',
            'cur_class',
            'cur_section',
            'cur_academic_year',
            'student_promotion_requests'
        ])->first();

        $promoRequest->update($requestArray);
        $promoRequest->update(['status' => 'REJECTED']);
        Session::flash('success', 'Promotion rejected successfully');
        return redirect()->back();
    }

    public function createRequestApproval(Request $request)
    {
        $id = $request->id;
        $promoRequest = PromotionRequest::where('id', $id)->with([
            'prev_branch',
            'prev_class',
            'prev_section',
            'prev_academic_year',
            'cur_branch',
            'cur_class',
            'cur_section',
            'cur_academic_year',
            'student_promotion_requests.student'
        ])->first();


        return view('promotion_requests.request_approval_modal', [
            'promoRequest' => $promoRequest
        ]);
    }

    public function storeRequestApproval(Request $request)
    {

        $requestArray = $request->validate([
            "record_id" => "required",
            "approved_by" => "required",
            "approved_date" => "required",
            "approval_remarks" => "required"
        ]);

        $promoRequest = PromotionRequest::where('id', $request->record_id)->with([
            'prev_branch',
            'prev_class',
            'prev_section',
            'prev_academic_year',
            'cur_branch',
            'cur_class',
            'cur_section',
            'cur_academic_year',
            'student_promotion_requests'
        ])->first();

        $promotion_requests = $promoRequest->student_promotion_requests->toArray();


        /*
         * Academic info
         * Student
         * */

        foreach ($promotion_requests as $promo_request) {
            $studentId = $promo_request['student_id'];

            $academic_info = ClassStudent::where([
                'student_id' => $studentId,
                'is_valid' => 1
            ])->with([
                        'students',
                        'academic_years',
                        'branch_class_sections.com_classes',
                        'branch_class_sections.sections'
                    ])->first();

            if (!isset($academic_info)) {
                return redirect()->back(302)->with('error', 'Student\'s academic info not found.');
            }

            $branch_class_section = BranchClassSection::find($promoRequest->cur_branch_class_section_id);

            if ($promoRequest->is_promotion) {
                // Make this working
                $result = $this->createGradeBookHistory($academic_info, $studentId);
                if (!$result['status']) {
                    Session::flash('error', $result['message']);
                    return redirect()->back();
                }
                $fee_package = FeePackage::where(['branch_id' => $promoRequest->cur_branch_id, 'academic_year_id' => $promoRequest->cur_academic_year_id])->whereHas('fee_package_type', function ($query) {
                    $query->where('name', 'Monthly');
                })->first();

                if ($fee_package == null) {
                    return redirect()->back(302)->with('error', 'Fee Package not found for the promoted branch and academic year. Please ensure a Monthly fee package exists for the promoted branch (' . $promoRequest->cur_branch_id . ') and academic year (' . $promoRequest->cur_academic_year_id . ') before approving the promotion.');
                } else {
                    $input = [
                        'fee_package_id' => $fee_package->id,
                        'fee_concession_id' => $request->fee_concession_id,
                        'academic_year_id' => $promoRequest->cur_academic_year_id,
                        'com_class_id' => $promoRequest->cur_class_id,
                        'section_id' => $promoRequest->cur_section_id,
                        'student_id' => $studentId
                    ];

                    StudentFeePackage::where(['student_id' => $studentId, 'is_valid' => 1])->update(['is_valid' => 0, 'active_till' => Carbon::now()]);
                    StudentFeePackage::create($input);
                }

                // Making old record inactive
                $academic_info->is_valid = 0;
                $academic_info->active_till = Carbon::now();
                $academic_info->save();

                // Creating new Academic Record
                $newAcademicInfo = ClassStudent::create([
                    'academic_year_id' => $promoRequest->cur_academic_year_id,
                    'branch_class_section_id' => $branch_class_section->id,
                    'student_id' => $studentId,
                    'is_valid' => 1,
                    'is_promoted' => 1
                ]);

                // Creating ledger
                $new_branch_academic_year = BranchAcademicYear::where([
                    'branch_id' => $branch_class_section->branch_id,
                    'academic_year_id' => $newAcademicInfo->academic_year_id
                ])->first();
                // $prev_academic_year_id = $promoRequest->prev_academic_year_id;
                // dd($prev_academic_year_id);

                $student_ledger = StudentLedger::create([
                    'student_id' => $studentId,
                    'class_student_id' => $newAcademicInfo->id
                ]);

                $academic_start_date = Carbon::parse($new_branch_academic_year->start_date)->format('m');

                StudentLedgerInvoice::create_empty_record($student_ledger['id'], $academic_start_date);
            } else {
                StudentFeePackage::where(['student_id' => $studentId, 'is_valid' => 1])->update(['is_valid' => 0, 'active_till' => Carbon::now()]);
                $academic_info->academic_year_id = $promoRequest->cur_academic_year_id;
                $academic_info->is_promoted = 0;
                $academic_info->is_valid = 0;
                $academic_info->active_till = Carbon::now();
                $academic_info->students->status = 'pass-out';
                $academic_info->students->save();
                $academic_info->save();
            }
        }
        $promoRequest->update($requestArray);
        $promoRequest->update(['status' => 'APPROVED']);
        Session::flash('success', 'Promoted to next class');
        return redirect()->back();
    }

    protected function createGradeBookHistory($academic_info, $studentId)
    {
        // dd($academic_info->toArray());
        $student_behaviour_skill = StudentBehaviourSkill::where('academic_year_id', '=', $academic_info->academic_year_id)
            ->where('branch_id', '=', $academic_info->branch_class_sections->branch_id)
            ->where('class_id', '=', $academic_info->branch_class_sections->class_id)
            ->where('section_id', '=', $academic_info->branch_class_sections->section_id)->first();
        if (!$student_behaviour_skill) {
            return array('status' => false, 'message' => 'Student\'s skill behaviour not found for the current academic year, branch, class, and section. Please ensure skill behaviour records are created for the promoted academic year, branch, class, and section before approving the promotion.');
        }
        $student_behaviour_skill_id = $student_behaviour_skill->id;
        $result = GradeBookHistory::create([
            'branch_class_section_id' => $academic_info->branch_class_sections->id,
            'student_id' => $studentId,
            'student_behaviour_skill_id' => $student_behaviour_skill_id,
        ]);

        return $result ? array('status' => true) : array('status' => false, 'message' => 'Student\'s grade history not created.');
    }

    /**
     * Validate class progression for promotion
     * 
     * @param int $currentBranchClassId
     * @param int $promotedBranchClassId
     * @return bool
     */
    protected function validateClassProgression($currentBranchClassId, $promotedBranchClassId)
    {
        // Get current class info
        $currentBranchClass = BranchClass::with('com_classes')->find($currentBranchClassId);
        if (!$currentBranchClass || !$currentBranchClass->com_classes) {
            return false;
        }

        // Get promoted class info
        $promotedBranchClass = BranchClass::with('com_classes')->find($promotedBranchClassId);
        if (!$promotedBranchClass || !$promotedBranchClass->com_classes) {
            return false;
        }

        $currentSort = $currentBranchClass->com_classes->sort;
        $promotedSort = $promotedBranchClass->com_classes->sort;

        // Simple validation: Only allow promotion to the next higher sort order
        return $this->validateSortProgression($currentSort, $promotedSort);
    }

    /**
     * Validate sort progression - simple sort order based validation
     * Only allows promotion to the next higher sort order (current + 1)
     * 
     * @param int $currentSort
     * @param int $promotedSort
     * @return bool
     */
    protected function validateSortProgression($currentSort, $promotedSort)
    {
        // Only allow promotion to the next higher sort order
        return $promotedSort == ($currentSort + 1);
    }
}
