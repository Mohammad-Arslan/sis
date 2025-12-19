<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessStudentImport;
use App\Models\Region;
use Log;
use Validator;
use App\Models\City;
use App\Models\Term;
use App\Models\Town;
use App\Models\Promo;
use App\Models\State;
use App\Models\Branch;
use App\Models\Country;
use App\Models\Section;
use App\Models\Student;
use App\Models\ComClass;
use App\Models\Guardian;
use App\Models\Language;
use App\Models\Relation;
use App\Models\Religion;
use App\Models\FeeCharge;
use App\Models\FeePeriod;
use App\Models\FeePackage;
use App\Models\BranchClass;
use App\Models\InvoiceType;
use App\Models\Nationality;
use Illuminate\Support\Str;
use App\Models\ClassStudent;
use App\Models\ClassSubject;
use Illuminate\Http\Request;
use App\Models\AcademicClass;
use App\Models\FeeConcession;
use App\Models\PaymentSource;
use App\Exports\ExportStudent;
use App\Services\ExportService;
use App\Models\AttachmentType;
use App\Models\StudentInvoice;
use App\Models\GeneralDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use App\Models\FamilyInformation;
use App\Models\StudentConcession;
use App\Models\StudentFeePackage;
use App\Models\BranchAcademicYear;
use App\Models\AcademicYear;
use App\Models\BranchClassSection;
use App\Models\StudentLedgerInvoice;
use Illuminate\Support\Facades\Auth;
use App\Models\FeePackagesFeeCharges;
use App\Models\StudentAssessmentMark;
use App\Models\StudentBehaviourSkill;
use App\Models\AssessmentLevel;
use App\Models\AssessmentEntry;
use App\Models\branch_security;
use App\Models\StudentPreviousSchool;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Imports\ImportStudent;
use Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View|DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = Student::studentListingQuery($request);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('full_name', function ($row) {
                        return view('students.student_image_tr', ['row' => $row]);
                    })
                    ->addColumn('reg_roll_no', function ($row) {
                        return $row->roll_no ?? $row->registration_no;
                    })
                    ->addColumn('class_section', function ($row) {
                        $class_name = $row['active_class']['branch_class_sections']['com_classes']['class_name'] ?? 'N/A';
                        $section_name = $row['active_class']['branch_class_sections']['sections']['section_name'] ?? 'N/A';
                        return $class_name . ' / ' . $section_name;
                    })
                    ->addColumn('academic_year_id', function ($row) use ($request) {
                        $classStudent = ClassStudent::where(['student_id' => $row->id, 'is_valid' => 1])->first();
                        return $classStudent && $classStudent->academic_years ? $classStudent->academic_years->title : 'N/A';
                    })
                    ->addColumn('security', function ($row) {
                        return view('students.student_security_tr', ['row' => $row]);
                    })
                    ->addColumn('action', function ($row) {
                        return view('students.actions', ['row' => $row]);
                    })
                    ->addColumn('status', function ($row) {
                        switch ($row->status) {
                            case 'on_roll':
                                $status = '<span class="badge bg-success">On Roll</span>';
                                break;
                            case 'registered':
                                $status = '<span class="badge bg-primary">Registered</span>';
                                break;
                            case 'left':
                                $status = '<span class="badge bg-danger">Left</span>';
                                break;
                            case 'pass-out':
                                $status = '<span class="badge bg-info">Pass Out</span>';
                                break;
                            default:
                                $status = '<span class="badge bg-danger">Processing</span>';
                        }

                        if ($row->from_branch !== null) {
                            $status .= '<br><span class="orange-bg">Transfered</span>';
                        }
                        return $status;
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        $academic_years = AcademicYear::all(['id', 'title', 'active']);
        $branches = Branch::all();
        $regions = Region::all();

        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes'])->get();
            $sections = Section::all();
        } elseif (auth()->user()->hasRole('senior-manager-business-development' || 'manager-business-development')) {
            $region_id = get_region_id();
            $regions = Region::where('id', $region_id)->get();
            $branches = $region_id != 0 ? Branch::where('region_id', $region_id)->get() : Branch::all();
            $classes = ComClass::all();
            $sections = Section::all();
        } else {
            $classes = ComClass::all();
            $sections = Section::all();
        }

        return view('students.students', compact(
            'classes',
            'sections',
            'branches',
            'academic_years',
            'regions'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = Guardian::with(['students', 'relation'])->get();
            // dd($data->toArray());
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    $fullName = $row->students->first_name . ' ' . $row->students->middle_name . ' ' . $row->students->last_name;
                    return $fullName;
                })


                ->addColumn('action', function ($row) {
                    return view('students.guardian_actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $branches = Branch::all();
        $students = Student::get();
        $relation = Relation::get();

        $countries = Country::get();
        $states = State::get();
        $cities = City::get();
        $towns = Town::get();
        $previous_schools = StudentPreviousSchool::orderBy("school_name")->get();

        $languages = Language::get();
        $nationalities = Nationality::get();
        $religions = Religion::get();
        return view('students.add_student', [
            'tab' => 'personal',
            // 'students' => $students,
            'relations' => $relation,
            'branches' => $branches,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'towns' => $towns,
            'languages' => $languages,
            'nationalities' => $nationalities,
            'religions' => $religions,
            'previous_schools' => $previous_schools,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                "first_name" => 'required|string|max:255',
                "middle_name" => 'nullable|string|max:255',
                "last_name" => 'required|string|max:255',
                "gender" => 'required|in:male,female,other',
                "date_of_birth" => 'required|date_format:d-m-Y',
                "email" => 'nullable|email|max:255',
                "religion_id" => 'required|exists:religions,id',
                "nationality_id" => 'required|exists:nationalities,id',
                "language_id" => 'required|exists:languages,id',
                "admission_wef" => 'required|date_format:d-m-Y',
                "registration_date" => 'required|date_format:d-m-Y',
                "test_date_time" => 'required|date_format:Y-m-d H:i',
                "interview_date_time" => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        if (! empty($value) && $value !== '') {
                            if (! preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $value)) {
                                $fail('Interview date time must be in YYYY-MM-DD HH:MM format (e.g., 2025-07-25 11:01).');
                            }
                        }
                    }
                ],
                "branch_id" => 'required|exists:branches,id',
                "cnic" => [
                    'required',
                    'string',
                    'max:15',
                    'regex:/^\d{5}-\d{7}-\d{1}$/',
                    'unique:students,cnic',
                ],
                "emergency_phone_number" => 'nullable|string|max:20',
                "birth_place" => 'nullable|string|max:255',
                "country_id" => 'required|exists:countries,id',
                "state_id" => 'required|exists:states,id',
                "city_id" => 'required|exists:cities,id',
            ], [
                'first_name.required' => 'First name is required.',
                'first_name.string' => 'First name must be a string.',
                'first_name.max' => 'First name cannot exceed 255 characters.',
                'middle_name.string' => 'Middle name must be a string.',
                'middle_name.max' => 'Middle name cannot exceed 255 characters.',
                'last_name.required' => 'Last name is required.',
                'last_name.string' => 'Last name must be a string.',
                'last_name.max' => 'Last name cannot exceed 255 characters.',
                'gender.required' => 'Gender is required.',
                'gender.in' => 'Gender must be either male, female, or other.',
                'date_of_birth.required' => 'Date of birth is required.',
                'date_of_birth.date_format' => 'Date of birth must be in DD-MM-YYYY format (e.g., 16-02-2016).',
                'email.email' => 'Email must be a valid email address.',
                'email.max' => 'Email cannot exceed 255 characters.',
                'religion_id.required' => 'Religion is required.',
                'religion_id.exists' => 'Selected religion does not exist.',
                'nationality_id.required' => 'Nationality is required.',
                'nationality_id.exists' => 'Selected nationality does not exist.',
                'language_id.required' => 'Language is required.',
                'language_id.exists' => 'Selected language does not exist.',
                'admission_wef.required' => 'Admission start date is required.',
                'admission_wef.date_format' => 'Admission start date must be in DD-MM-YYYY format (e.g., 06-06-2025).',
                'registration_date.required' => 'Registration date is required.',
                'registration_date.date_format' => 'Registration date must be in DD-MM-YYYY format (e.g., 06-06-2025).',
                'test_date_time.required' => 'Test date time is required.',
                'test_date_time.date_format' => 'Test date time must be in YYYY-MM-DD HH:MM format (e.g., 2025-07-25 11:01).',
                'interview_date_time.date_format' => 'Interview date time must be in YYYY-MM-DD HH:MM format (e.g., 2025-07-25 11:01).',
                'branch_id.required' => 'Branch is required.',
                'branch_id.exists' => 'Selected branch does not exist.',
                'cnic.required' => 'CNIC is required.',
                'cnic.string' => 'CNIC must be a string.',
                'cnic.max' => 'CNIC cannot exceed 15 characters.',
                'cnic.regex' => 'CNIC must be in the format 55555-5555555-5.',
                'cnic.unique' => 'CNIC is already in use.',
                'emergency_phone_number.string' => 'Emergency phone number must be a string.',
                'emergency_phone_number.max' => 'Emergency phone number cannot exceed 20 characters.',
                'birth_place.string' => 'Birth place must be a string.',
                'birth_place.max' => 'Birth place cannot exceed 255 characters.',
                'country_id.required' => 'Country is required.',
                'country_id.exists' => 'Selected country does not exist.',
                'state_id.required' => 'State is required.',
                'state_id.exists' => 'Selected state does not exist.',
                'city_id.required' => 'City is required.',
                'city_id.exists' => 'Selected city does not exist.',
            ]);

            DB::beginTransaction();

            $inputs = $request->all();
            if (isset($inputs['security_deposit'])) {
                $inputs['security_number'] = intval(round(random_int(1000, 9999) + microtime(true)));
            }

            $student = Student::create($inputs);
            Student::attach_reg_no($student->id);

            DB::commit();

            return redirect()
                ->route('students.edit', ['student' => $student->id, 'tab' => 'personal'])
                ->with('success', 'Student created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Student $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Student $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Student $student)
    {
        if (isset($request->e) && $request->e > 0) {
            $std_concession = StudentConcession::find($request->e);
        } else {
            $std_concession = null;
        }

        $student = $student->load('student_attendance');

        $branches = Branch::all();
        $relation = Relation::get();

        $countries = Country::get();
        $states = State::get();
        $cities = City::get();
        $towns = Town::get();
        $previous_schools = StudentPreviousSchool::orderBy("school_name")->get();

        $languages = Language::get();
        $nationalities = Nationality::get();
        $religions = Religion::get();
        $class_id = 0;
        if (isset($student->active_class->branch_class_sections->class_id)) {
            $class_id = $student->active_class->branch_class_sections->class_id;
        }

        $academic_years = BranchAcademicYear::where('branch_id', $student->branch_id)->with('academic_year')->get();
        $all_academic_years = AcademicYear::all();
        $branch_class_sections = BranchClassSection::where('branch_id', $student->branch_id)->with(['branches', 'sections', 'com_classes']);
        $assessment_subjects = getClassSubjects($class_id, $student->branch_id);
        $assessement_branch_classes = BranchClass::with('com_classes')->where('branch_id', $student->branch_id)->with(['com_classes'])->get();
        $assessment_branch_class_sections = BranchClassSection::with('sections')->where(['branch_id' => $student->branch_id, 'class_id' => $class_id])->get();
        // dd($assessment_subjects,$assessement_branch_classes,$assessment_branch_class_sections);
        // $branch_class_sections = BranchClassSection::whereHas('branch_class_sections', function ($query) use ($student) {
        //     $query->where('branch_id', $student->branch_id);
        // })->with(['branch_class_sections.sections', 'branch_class_sections.com_classes', 'academic_years'])->get();
        $classes = BranchClass::where('branch_id', $student->branch_id)->with(['com_classes'])->get();
        $student_first_guardian = Student::where('id', $student->id)->with('first_guardian.family.children.student')->first();
        // dd($student_first_guardian->toArray());
        $invoice_types = InvoiceType::all();
        $payment_sources = PaymentSource::all();
        $promos = Promo::where('branch_id', $student->branch_id)->get();
        $additional_charges = FeeCharge::where(['branch_id' => $student->branch_id])->whereNull('fee_package_id')->with('fee_charges_type')->get();
        $student_fee_package = StudentFeePackage::where(['student_id' => $student->id, 'is_valid' => 1])->with(['fee_package.fee_package_type', 'fee_concession.fee_concession_type', 'academic_year', 'com_class', 'section'])->first();

        // Get fee charges - if student has an active package, show only package charges, otherwise show all branch charges
        if ($student_fee_package) {
            $package_charges = FeePackagesFeeCharges::where([
                'fee_package_id' => $student_fee_package->fee_package_id,
                'status' => 1
            ])
            ->with(['fee_charges.fee_charges_type'])
            ->get();

            // Extract the actual fee_charges from the package charges
            $fee_charges = collect();
            foreach ($package_charges as $package_charge) {
                if ($package_charge->fee_charges) {
                    $fee_charges->push($package_charge->fee_charges);
                }
            }

            // If no package charges found, fall back to all branch charges
            if ($fee_charges->isEmpty()) {
                $fee_charges = FeeCharge::where(['branch_id' => $student->branch_id])->with('fee_charges_type')->get();
            }
        } else {
            $fee_charges = FeeCharge::where(['branch_id' => $student->branch_id])->with('fee_charges_type')->get();
        }

        $check_for_student_class = ClassStudent::where(['student_id' => $student->id, 'is_valid' => 1])->with('branch_class_sections.com_classes');
        $check_if_student_admitted = StudentInvoice::where(['student_id' => $student->id, 'invoice_frequency' => 'Admission', 'is_paid' => 1]);

        $student_current_class = $check_for_student_class->first();

        if ($check_for_student_class->exists()) {
            if ($check_if_student_admitted->exists()) {
                $fee_packages = FeePackage::where(['branch_id' => $student->branch_id])
                    ->whereHas('fee_package_type', function ($query) {
                        $query->where('name', 'Monthly');
                        //$query->where('name', 'Admission');
                    })
                    ->whereHas('classes', function ($query) use ($student_current_class) {
                        $query->where('com_classes.id', $student_current_class->branch_class_sections->com_classes->id);
                    })
                    ->get();
            } else {
                // $fee_packages = FeePackage::where(['branch_id' => $student->branch_id])
                //     ->whereHas('fee_package_type', function ($query) {
                //         $query->where('name', 'Admission');
                //         //$query->where('name', 'Monthly');
                //     })
                //     ->whereHas('classes', function ($query) use ($student_current_class) {
                //         $query->where('com_classes.id', $student_current_class->branch_class_sections->com_classes->id);
                //     })
                //     ->get();

                $fee_packages = FeePackage::where(['branch_id' => $student->branch_id])
                    ->whereHas('fee_package_type', function ($query) {
                        $query->where('name', 'Admission');
                    })
                    ->where(function ($query) use ($student_current_class) {
                        $query->whereHas('classes', function ($subQuery) use ($student_current_class) {
                            $subQuery->where('com_classes.id', $student_current_class->branch_class_sections->com_classes->id);
                        })
                            ->orWhereDoesntHave('classes'); // This line allows fee packages with no class mapping
                    })
                    ->get();
            }
            $fee_concessions = [];
            if (isset($student_first_guardian->first_guardian->family)) {
                if (count($student_first_guardian->first_guardian->family->children) > 1) {
                    foreach ($student_first_guardian->first_guardian->family->children as $child) {
                        if ($child->status == "on_roll") {
                            $fee_concessions = FeeConcession::where(['branch_id' => $student->branch_id, 'academic_year_id' => $student_current_class->academic_year_id])
                                ->with('fee_concession_type')
                                ->whereHas('fee_concession_type', function ($query) {
                                    $query->orWhere('name', 'LIKE', '%Sibling%');
                                })
                                ->get();
                            break;
                        }
                    }
                    if (count($fee_concessions) == 0) {
                        $fee_concessions = FeeConcession::where(['branch_id' => $student->branch_id, 'academic_year_id' => $student_current_class->academic_year_id])
                            ->with('fee_concession_type')
                            ->whereHas('fee_concession_type', function ($query) {
                                $query->where('name', 'not LIKE', '%Sibling%');
                            })
                            ->get();
                    }
                } else {
                    $fee_concessions = FeeConcession::where(['branch_id' => $student->branch_id, 'academic_year_id' => $student_current_class->academic_year_id])
                        ->with('fee_concession_type')
                        ->whereHas('fee_concession_type', function ($query) {
                            $query->where('name', 'not LIKE', '%Sibling%');
                        })
                        ->get();
                }
            } else {
                $fee_concessions = FeeConcession::where(['branch_id' => $student->branch_id, 'academic_year_id' => $student_current_class->academic_year_id])
                    ->with('fee_concession_type')
                    ->whereHas('fee_concession_type', function ($query) {
                        $query->where('name', 'not LIKE', '%Sibling%');
                    })
                    ->get();
            }
            $fee_periods = FeePeriod::where(['branch_id' => $student->branch_id, 'academic_year_id' => $student_current_class->academic_year_id]);
            if (! empty($student->admission_wef)) {
                $fee_periods->whereDate('from_date', '>=', $student->admission_wef);
            }
            $fee_periods = $fee_periods->get();

            $check_student_admission_status = StudentInvoice::where(['student_id' => $student->id, 'invoice_frequency' => 'Admission', 'is_paid' => 1])->whereHas('student_fee_package', function ($query) use ($student_current_class) {
                $query->where(['academic_year_id' => $student_current_class->academic_year_id, 'com_class_id' => $student_current_class->branch_class_sections->class_id, 'section_id' => $student_current_class->branch_class_sections->section_id]);
            })->exists();
        } else {
            $fee_concessions = [];
            $fee_packages = [];
            $fee_periods = [];
            $check_student_admission_status = 0;
        }

        $attachment_types = AttachmentType::where('module', 'student')->get();
        $last_invoice_month = null;
        $existing_fee_period_invoice1 = StudentInvoice::where([
            'student_id' => $student->id,
        ])->where('bank_payment_status', '!=', 'cancelled')->get()->toArray();
        $existing_fee_period_invoice = StudentInvoice::where([
            'student_id' => $student->id,
        ])->where('bank_payment_status', '!=', 'cancelled');
        if (isset($student_fee_package['fee_package']['fee_package_type']['name']) && $student_fee_package['fee_package']['fee_package_type']['name'] == 'Monthly') {
            $TF_in_admission_invoice = StudentInvoice::whereHas('student_invoice_items.fee_charges.fee_charges_type', function ($q) {
                $q->where('abbreviation', 'TF');
            })->where(['student_id' => $student->id, 'invoice_frequency' => 'Admission'])->first();
            $existing_invoice = ! empty($existing_fee_period_invoice1) ? StudentLedgerInvoice::where('student_invoice_id', $existing_fee_period_invoice1[0]['id'])->get()->toArray() : [];
            //    dd($existing_invoice->toArray());
            if (empty($TF_in_admission_invoice)) {
                $admission_invoice = StudentInvoice::where(['student_id' => $student->id, 'invoice_frequency' => 'Admission'])->first();
                if ($admission_invoice) {
                    $existing_fee_period_invoice = $existing_fee_period_invoice->where('id', '!=', $admission_invoice->id);
                }
            }
            if (isset($existing_invoice) && ! empty($existing_invoice) && isset($existing_invoice[0]['month'])) {
                $last_invoice_month = $existing_invoice[0]['month'];
                // dd($last_invoice_month);
                if ($last_invoice_month == 12) {
                    $last_invoice_month = 0;
                }
            }
        }

        // dd($last_invoice_month);
        $existing_fee_period_invoice = $existing_fee_period_invoice->pluck('fee_period_id')->toArray();
        // dd($existing_fee_period_invoice);
        $family = FamilyInformation::whereHas('children', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })->with('parent.relation')->first();
        // $student_academic_year = BranchAcademicYear::where(['branch_id' => $student->branch_id, 'academic_year_id' => $student_fee_package->academic_year->id])->with('academic_year')->first();
        // dd($check_student_admission_status);
        // dd($fee_concessions->toArray());

        $terms = Term::all();
        // $previous_schools = StudentPreviousSchool::all();

        // $student_subjects = ClassSubject::where('class_id', $student_active_class->active_class->branch_class_sections->class_id)->with('subject')->get();

        if ($student->security_amount == null) {
            $security = branch_security::where('branch_id', $student->branch_id)->where('academic_year_id', $student->admission_year_id)->first();
            if (isset($security->amount)) {
                $student['amount'] = $security->amount;
            }
        } else {
            $student['amount'] = $student->security_amount;
        }
        // Student assessment
        $assessment_level_one = AssessmentLevel::where('parent_id', 0)->get();
        $response = [
            'tab' => 'personal',
            'student' => $student,
            'relations' => $relation,
            'branches' => $branches,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'towns' => $towns,
            'languages' => $languages,
            'nationalities' => $nationalities,
            'religions' => $religions,
            'fee_packages' => $fee_packages,
            'fee_concessions' => $fee_concessions,
            'fee_charges' => $fee_charges,
            'academic_years' => $academic_years,
            'branch_class_sections' => $branch_class_sections,
            'classes' => $classes,
            'student_fee_package' => $student_fee_package,
            // 'student_academic_year' => $student_academic_year,
            'check_student_admission_status' => $check_student_admission_status,
            'invoice_types' => $invoice_types,
            'payment_sources' => $payment_sources,
            'fee_periods' => $fee_periods,
            'promos' => $promos,
            'additional_charges' => $additional_charges,
            'attachment_types' => $attachment_types,
            'existing_fee_period_invoice' => $existing_fee_period_invoice,
            'family' => $family,
            'terms' => $terms,
            'previous_schools' => $previous_schools,
            'last_invoice_month' => $last_invoice_month,
            'student_concession' => $std_concession,
            'assessment_level_one' => $assessment_level_one,
            'all_academic_years' => $all_academic_years,
            'assessment_subjects' => $assessment_subjects,
            'assessement_branch_classes' => $assessement_branch_classes,
            'assessment_branch_class_sections' => $assessment_branch_class_sections,
            'guardians' => $student_first_guardian->first_guardian
        ];

        if ($student->student_address()->exists()) {
            $response['student_address'] = $student->load('student_address')->student_address;
        }

        // dd($student_fee_package);
        if (isset($student_fee_package)) {
            $student_package_charges = FeePackagesFeeCharges::where([
                'fee_package_id' => $student_fee_package->fee_package_id,
                'status' => 1
            ])
                ->with([
                    'fee_charges.fee_charges_type',
                    'fee_charges.student_concessions' => function ($query) use ($student) {
                        $query->where('student_id', $student->id);
                        $query->where('is_valid', 1);
                    },
                    'fee_charges.student_concessions.fee_concession'
                ])
                ->get();

            $response['student_package_charges'] = $student_package_charges;
        }

        if (isset($request->general_document_id)) {
            $response['general_document'] = GeneralDocument::find($request->general_document_id);
        }

        return view('students.add_student', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Student $student
     * @return RedirectResponse
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        try {
            if (isset($request->student_image)) {
                $request->validate([
                    'student_image' => 'mimetypes:image/png,image/jpeg,image/jpg|file|max:10000',
                ]);
                $this->upload_student_image($request, $student);
                return redirect()
                    ->route('students.edit', ['student' => $student->id, 'tab' => 'student_image'])
                    ->with('success', 'Student image uploaded successfully.');
            }

            $validated = $request->validate([
                "first_name" => 'required|string|max:255',
                "middle_name" => 'nullable|string|max:255',
                "last_name" => 'required|string|max:255',
                "gender" => 'required|in:male,female,other',
                "date_of_birth" => 'required|date_format:d-m-Y',
                "email" => 'nullable|email|max:255',
                "religion_id" => 'required|exists:religions,id',
                "nationality_id" => 'required|exists:nationalities,id',
                "language_id" => 'required|exists:languages,id',
                "admission_wef" => 'required|date_format:d-m-Y',
                "registration_date" => 'required|date_format:d-m-Y',
                "test_date_time" => 'required|date_format:Y-m-d H:i',
                "interview_date_time" => 'nullable|date_format:Y-m-d H:i|prohibited_if:interview_date_time,',
                "branch_id" => 'required|exists:branches,id',
                "cnic" => [
                    'required',
                    'string',
                    'max:15',
                    'regex:/^\d{5}-\d{7}-\d{1}$/',
                    'unique:students,cnic,' . $student->id,
                ],
                "emergency_phone_number" => 'nullable|string|max:20',
                "birth_place" => 'nullable|string|max:255',
                "pin_code" => 'nullable|string|max:255',
                "card_no" => 'nullable|string|max:255',
                "country_id" => 'required|exists:countries,id',
                "state_id" => 'required|exists:states,id',
                "city_id" => 'required|exists:cities,id',
            ], [
                'first_name.required' => 'First name is required.',
                'first_name.string' => 'First name must be a string.',
                'first_name.max' => 'First name cannot exceed 255 characters.',
                'middle_name.string' => 'Middle name must be a string.',
                'middle_name.max' => 'Middle name cannot exceed 255 characters.',
                'last_name.required' => 'Last name is required.',
                'last_name.string' => 'Last name must be a string.',
                'last_name.max' => 'Last name cannot exceed 255 characters.',
                'gender.required' => 'Gender is required.',
                'gender.in' => 'Gender must be either male, female, or other.',
                'date_of_birth.required' => 'Date of birth is required.',
                'date_of_birth.date_format' => 'Date of birth must be in DD-MM-YYYY format (e.g., 16-02-2016).',
                'email.email' => 'Email must be a valid email address.',
                'email.max' => 'Email cannot exceed 255 characters.',
                'religion_id.required' => 'Religion is required.',
                'religion_id.exists' => 'Selected religion does not exist.',
                'nationality_id.required' => 'Nationality is required.',
                'nationality_id.exists' => 'Selected nationality does not exist.',
                'language_id.required' => 'Language is required.',
                'language_id.exists' => 'Selected language does not exist.',
                'admission_wef.required' => 'Admission start date is required.',
                'admission_wef.date_format' => 'Admission start date must be in DD-MM-YYYY format (e.g., 06-06-2025).',
                'registration_date.required' => 'Registration date is required.',
                'registration_date.date_format' => 'Registration date must be in DD-MM-YYYY format (e.g., 06-06-2025).',
                'test_date_time.required' => 'Test date time is required.',
                'test_date_time.date_format' => 'Test date time must be in YYYY-MM-DD HH:MM format (e.g., 2025-07-25 11:01).',
                'branch_id.required' => 'Branch is required.',
                'branch_id.exists' => 'Selected branch does not exist.',
                'cnic.required' => 'CNIC is required.',
                'cnic.string' => 'CNIC must be a string.',
                'cnic.max' => 'CNIC cannot exceed 15 characters.',
                'cnic.regex' => 'CNIC must be in the format 55555-5555555-5.',
                'cnic.unique' => 'CNIC is already in use.',
                'emergency_phone_number.string' => 'Emergency phone number must be a string.',
                'emergency_phone_number.max' => 'Emergency phone number cannot exceed 20 characters.',
                'birth_place.string' => 'Birth place must be a string.',
                'birth_place.max' => 'Birth place cannot exceed 255 characters.',
                'pin_code.string' => 'Pin code must be a string.',
                'pin_code.max' => 'Pin code cannot exceed 255 characters.',
                'card_no.string' => 'Card number must be a string.',
                'card_no.max' => 'Card number cannot exceed 255 characters.',
                'country_id.required' => 'Country is required.',
                'country_id.exists' => 'Selected country does not exist.',
                'state_id.required' => 'State is required.',
                'state_id.exists' => 'Selected state does not exist.',
                'city_id.required' => 'City is required.',
                'city_id.exists' => 'Selected city does not exist.',
            ]);

            DB::beginTransaction();

            $inputs = $request->all();
            if (! isset($inputs['security_deposit'])) {
                $inputs['security_deposit'] = 0;
                $inputs['security_number'] = null;
            } else {
                $security_number = $student->security_number;
                if ($security_number === null) {
                    $inputs['security_number'] = intval(round(random_int(1000, 9999) + microtime(true)));
                } else {
                    $inputs['security_number'] = $security_number;
                }
            }

            $student->update($inputs);

            DB::commit();

            return redirect()
                ->route('students.edit', ['student' => $student->id, 'tab' => 'personal'])
                ->with('success', 'Student updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $student
     * @return bool
     */
    public function destroy(Student $student): bool
    {
        try {
            DB::beginTransaction();

            // Delete child records in the correct order
            $student->student_behaviour_skill_remarks()->delete();
            $student->student_behaviour_skill_marks()->delete();
            $student->student_attendance()->delete();
            $student->student_concession()->delete();
            $student->student_fee_package()->delete();
            $student->class_students()->delete();
            $student->student_invoices()->delete();
            $student->payments()->delete();
            $student->arrears_history()->delete();
            $student->invoices()->delete();
            if ($student->sibling_info) {
                $student->sibling_info()->delete();
            }
            $student->general_documents()->delete();
            if ($student->student_promotion_request) {
                $student->student_promotion_request()->delete();
            }
            if ($student->student_withdrawals) {
                $student->student_withdrawals()->delete();
            }
            if ($student->ptm_info) {
                $student->ptm_info()->delete();
            }
            if ($student->extra_curriculum_info) {
                $student->extra_curriculum_info()->delete();
            }
            $student->guardians()->delete();
            $student->student_address()->delete();

            $result = $student->delete();

            DB::commit();

            return $result;
        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function create_registration_slip($student_id, $type)
    {
        $data['type'] = $type;
        $data['student'] = Student::with([
            'guardian',
            'student_address',
            'active_class' => function ($q) {
                $q->with([
                    'branch_class_sections.com_classes',
                    'branch_class_sections.sections',
                ]);
            },
        ])->where('id', $student_id)->first();

        if ($type == 'modal') {
            return view('students.registration_slip_modal', $data);
        }
        $pdf = Pdf::loadView('students.registration_slip_pdf', $data);
        return $pdf->download('RegistrationSlip.pdf');
    }

    /**
     * Upload student image
     *
     * @param Request $request
     * @param Student $student
     * @return void
     */
    protected function upload_student_image(Request $request, Student $student): void
    {
        try {
            if ($request->hasFile('student_image')) {
                $student_img_filename = Str::random(32) . '.' . $request->student_image->getClientOriginalExtension();
                $filepath = 'images/' . $student_img_filename;

                Storage::disk('s3')->put($filepath, file_get_contents($request->student_image));

                if ($student->student_image && Storage::disk('s3')->exists('images/' . $student->student_image)) {
                    Storage::disk('s3')->delete('images/' . $student->student_image);
                }

                $student->update(['student_image' => $student_img_filename]);
            }
        } catch (\Exception $e) {
            throw new \Exception('Failed to upload student image: ' . $e->getMessage());
        }
    }

    /**
     * Export students to Excel
     *
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function exportStudents(Request $request): BinaryFileResponse
    {
        // Configure memory and execution time for large exports
        ExportService::configureForLargeExport();

        // Get the appropriate export class based on dataset size
        $exportClass = ExportService::getExportClass($request);

        return \Maatwebsite\Excel\Facades\Excel::download(new $exportClass($request), 'Students.xlsx');
    }

    public function studentAssessments(Request $request)
    {
        if ($request->ajax()) {
            $query = array();

            if (isset($request->section_id)) {
                $query = AssessmentEntry::with([
                    'assessment_level_one',
                    'assessment_level_two',
                    'assessment_level_three',
                    'academic_year',
                    'branch',
                    'term',
                    'section',
                    'com_class',
                    'subject',
                ])->select('assessment_entries.*', 'assessment_entries.id as assessment_entry_id');

                if (isset($request->academic_year_id)) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('academic_year_id', $request->academic_year_id);
                        $q->orWhereNull('academic_year_id');
                    });
                }

                if (isset($request->branch_id)) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('assessment_entries.branch_id', $request->branch_id);
                        $q->orWhereNull('assessment_entries.branch_id');
                    });
                }

                if (isset($request->class_id)) {
                    $branch_class = BranchClass::find($request->class_id);
                    $class_id = ! empty($branch_class) ? $branch_class->class_id : 0;
                    $query = $query->where(function ($q) use ($class_id) {
                        $q->where('class_id', $class_id);
                        $q->orWhereNull('class_id');
                    });
                }

                if (isset($request->section_id)) {
                    $branch_class_section = BranchClassSection::find($request->section_id);
                    $section_id = ! empty($branch_class_section) ? $branch_class_section->section_id : 0;
                    $query = $query->where(function ($q) use ($section_id) {
                        $q->where('section_id', $section_id);
                        $q->orWhereNull('section_id');
                    });
                }

                if (isset($request->subject_id)) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('subject_id', $request->subject_id);
                        $q->orWhereNull('subject_id');
                    });
                }

                if (isset($request->term_id)) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('term_id', $request->term_id);
                        $q->orWhereNull('term_id');
                    });
                }
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('level_three_name', function ($row) {
                    return isset($row['assessment_level_three']) ? $row['assessment_level_three']['name'] : '';
                })
                ->addColumn('subject', function ($row) {
                    return isset($row['subject']) ? $row['subject']['subject_name'] : '';
                })
                ->make(true);
        }
    }

    public function updatePreviousSchool(Request $request)
    {
        $student = Student::where('id', $request->student_id)->update(['previous_school_id' => $request->school_id]);
        return redirect()->back()->with('success', 'Student previous School updated successfully.');

        /*if ( $request -> ajax() ) {
            return response() -> json([
                'status' => 201,
                'success' => 'Student previous School updated successfully.',
            ]);
        }*/
    }

    public function printLeavingCase(Request $request)
    {

        $type = $request->type;
        $student_id = $request->id;
        $showFormInfo = false;

        //If no student if is passed, show blank form
        if (empty($student_id)) {
            $data = [''];
        } else {
            $certificate_number = Student::where('id', $student_id)->get('certificate_number');
            if (is_null($certificate_number[0]->certificate_number)) {
                Student::where('id', $student_id)->update(['certificate_number' => random_int(10000, 99999)]);
            }

            $showFormInfo = true;
            $studentInfo = Student::where('id', $student_id)->with(
                'active_class.branch_class_sections.com_classes',
                'active_class.branch_class_sections.sections',
                'branch',
                'state',
                'guardian',
                'student_withdrawals'
            )->first();
        }
        // dd($studentInfo->toArray());


        if ($type == 'modal') {
            return view('students.leaving_certificate_form_modal', ['showFormInfo' => $showFormInfo, 'studentInfo' => $studentInfo, 'type' => $type]);
        } else {
            $pdf = PDF::loadView('students.leaving_print_form_pdf', ['type' => $type, 'showFormInfo' => $showFormInfo]);
            return $pdf->download('StudentLeavingCertificate.pdf');
        }
    }

    public function certificateVerification(Request $request)
    {

        $studentInfo = Student::where('certificate_number', $request->query('cn'))->with(
            'active_class.branch_class_sections.com_classes',
            'active_class.branch_class_sections.sections',
            'branch',
            'state',
            'guardian',
            'student_withdrawals'
        )->first();
        //dd($studentInfo->toArray());
        return view('students.verification', ['studentInfo' => $studentInfo]);
    }

    /**
     * Generate security challan
     *
     * @param int $student_id
     * @return \Illuminate\Http\Response
     */
    public function generateSecurityChallan(int $student_id)
    {
        try {
            $data['securityChallan'] = Student::with([
                'branch',
                'country',
                'state',
                'city',
                'language',
                'nationality',
                'religion',
                'guardian',
                'student_address',
                'active_class.branch_class_sections.com_classes',
                'active_class.branch_class_sections.sections',
                'branch.default_bank_account',
            ])->findOrFail($student_id);

            if (! isset($data['securityChallan']['branch']['default_bank_account']['id'])) {
                return redirect()->back()->with('error', 'Bank Account Not Available');
            }

            if ($data['securityChallan']['security_amount'] === null) {
                $security = branch_security::where('branch_id', $data['securityChallan']['branch']['id'])
                    ->where('academic_year_id', $data['securityChallan']['admission_year_id'])
                    ->first();

                $data['securityChallan']['amount'] = $security ? $security->amount : null;
            } else {
                $data['securityChallan']['amount'] = $data['securityChallan']['security_amount'];
            }

            ini_set('max_execution_time', 180);
            $pdf = Pdf::loadView('students.student_security_challan', ['challan' => array_merge([], [$data])])
                ->setPaper('a4', 'portrait');

            return $pdf->stream($data['securityChallan']['last_name'] . ' Challan.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate security challan: ' . $e->getMessage());
        }
    }

    public function openImportModal()
    {
        return view('students.student_import_modal', []);
    }

    public function importStudent(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:' . (config('import.student_import.max_file_size') * 2024),
        ]);

        try {
            // Store the file temporarily
            $filePath = $request->file('file')->store('temp/imports');

            // Dispatch the import job to the queue
            ProcessStudentImport::dispatch($filePath, auth()->id());

            return response()->json([
                'success' => 'Import has been queued and will be processed in the background. Check the logs for progress.',
                'queued' => true,
                'message' => 'Your file is being processed. Large imports may take several minutes to complete.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['An error occurred while queuing the import: ' . $e->getMessage()]], 500);
        }
    }

    /**
     * Search students with on_roll status for Select2 dropdown
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchOnRollStudents(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $page = $request->get('page', 1);
            $perPage = 20;

            $query = Student::where('status', 'on_roll')
                ->with(['active_class.branch_class_sections.com_classes']);

            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('registration_no', 'LIKE', "%{$search}%")
                      ->orWhere('first_name', 'LIKE', "%{$search}%")
                      ->orWhere('last_name', 'LIKE', "%{$search}%")
                      ->orWhere('roll_no', 'LIKE', "%{$search}%");
                });
            }

            $students = $query->orderBy('first_name')
                ->orderBy('last_name')
                ->paginate($perPage, ['*'], 'page', $page);

            $formattedStudents = $students->getCollection()->map(function ($student) {
                return [
                    'id' => $student->id,
                    'registration_no' => $student->registration_no,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'roll_no' => $student->roll_no,
                    'class_name' => $student->active_class && $student->active_class->branch_class_sections && $student->active_class->branch_class_sections->com_classes
                        ? $student->active_class->branch_class_sections->com_classes->class_name
                        : 'N/A'
                ];
            });

            return response()->json([
                'data' => $formattedStudents,
                'next_page_url' => $students->nextPageUrl(),
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to search students: ' . $e->getMessage()
            ], 500);
        }
    }
}
