<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Term;
use App\Models\Town;
use App\Models\Week;
use App\Models\Skill;
use App\Models\State;
use App\Models\Branch;
use App\Models\Country;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ComClass;
use App\Models\Guardian;
use App\Models\FeePeriod;
use App\Models\FeePackage;
use App\Models\BranchClass;
use App\Models\AcademicYear;
use App\Models\ClassStudent;
use App\Models\ClassTeacher;
use Illuminate\Http\Request;
use App\Models\StudentInvoice;
use App\Models\AssessmentEntry;
use App\Models\AssessmentLevel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\NetworkAssociate;
use App\Models\BranchAcademicYear;
use App\Models\BranchClassSection;
use App\Models\ClassStudentSubject;
use App\Models\StudentAssessmentMark;
use App\Models\StudentBehaviourSkill;

use function PHPUnit\Framework\isEmpty;

use Yajra\DataTables\Facades\DataTables;

class CommonController extends Controller
{
    public function listStates(Request $request)
    {
        $data = State::where('country_id', $request->id)->get();
        return response()->json($data);
    }

    public function listCities(Request $request)
    {
        $data = City::where('state_id', $request->id)->get();
        return response()->json($data);
    }

    public function listTowns(Request $request)
    {
        $data = Town::where('city_id', $request->id)->get();
        return response()->json($data);
    }

    public function listBranches(Request $request)
    {
        $data = Branch::where('company_id', $request->id)->get();
        return response()->json($data);
    }

    public function listAcademicBranches(Request $request)
    {
        $branches = BranchAcademicYear::where('academic_year_id', $request->id)->with('branch')->get();
        $data = [
            'branches' => $branches
        ];
        return response()->json($data);
    }

    public function listAcademicBranchClasses(Request $request)
    {
        $classes = BranchClass::where('branch_id', $request->id)->with(['com_classes'])->get();
        $data = [
            'classes' => $classes
        ];
        return response()->json($data);
    }

    public function listAcademicBranchClassSection(Request $request)
    {
        $sections = BranchClassSection::where(['branch_id' => $request->branch_id, 'class_id' => $request->id])->with(['sections'])->get();
        $data = [
            'sections' => $sections
        ];
        return response()->json($data);
    }

    public function listSkills(Request $request)
    {
        $data = Skill::where([['status', 'active'], ['class_id', $request->id]])->get();
        return response()->json($data);
    }

    public function listBranchesByRegion(Request $request)
    {
        $data = Branch::where('region_id', $request->id)->get();
        return response()->json($data);
    }

    public function listBranchesByState(Request $request)
    {
        $data = Branch::whereHas('contact_information', function ($query) use ($request) {
            $query->where('state_id', $request->id);
        })->get();
        return response()->json($data);
    }

    public function listAcademicYears(Request $request)
    {
        $data = BranchAcademicYear::where('branch_id', $request->id)->with('academic_year')->get();
        return response()->json($data);
    }

    public function listNetworkAssociates(Request $request)
    {
        $data = NetworkAssociate::where('company_id', $request->id)->with('user')->get();
        return response()->json($data);
    }

    public function listSection(Request $request, $branch)
    {
        if ($request->type != 'bulk') {
            $data = BranchClassSection::where(['branch_id' => $branch, 'class_id' => $request->id])->with(['com_classes', 'sections'])->get();
            return response()->json($data);
        }

        $branches = explode(',', $branch);
        $classes = explode(',', $request->classes);

        $data = BranchClassSection::whereIn('branch_id', $branches)->whereIn('class_id', $classes)->with(['com_classes', 'sections', 'branches'])->get();
        return response()->json($data);
    }

    public function listBranchClasses(Request $request)
    {
        if (isset($request->id)) {
            $data = BranchClass::where('branch_id', $request->id)->with(['com_classes'])->get();
            return response()->json($data);
        }
    }

    public function getBranchClasses(Request $request)
    {

        if (isset($request->id)) {
            $classdata = BranchClass::where('branch_id', $request->id)->with(['com_classes'])->get();

            $i = 0;
            foreach ($classdata as $class) {
                $data[$i]['id'] = $class->class_id;
                $data[$i]['class_name'] = $class->com_classes->class_name;
                $i++;
            }
            return response()->json($data);
        }
    }

    public function listBranchClassesMonths(Request $request)
    {
        if (isset($request->id)) {
            $data = BranchClass::where('branch_id', $request->id)->with(['com_classes'])->get()->pluck('com_classes');
            $current_acad_year = get_current_acad_year_by_branch_id($request->id);

            $data = [
                'classes' => $data,
                'months' => getMonthListFromDate($current_acad_year->start_date, $current_acad_year->end_date)
            ];

            return response()->json($data);
        }
    }

    public function listBranchClassesSections(Request $request)
    {
        $branch_class = BranchClass::find($request->id);

        if (isset($request->id)) {
            $data = BranchClassSection::where(['branch_id' => $branch_class->branch_id, 'class_id' => $branch_class->class_id])->with(['branches', 'com_classes', 'sections'])->get();
            return response()->json($data);
        }
    }

    public function getBranchClassesSections(Request $request)
    {

        if (isset($request)) {
            $sectiondata = StudentBehaviourSkill::where(['branch_id' => $request->query('branch_id'), 'class_id' => $request->query('class_id')])->with(['branch', 'com_class', 'section'])->get();
            $i = 0;
            foreach ($sectiondata as $section) {
                $data[$i]['id'] = $section->section->id;
                $data[$i]['section_name'] = $section->section->section_name;
                $i++;
            }
            return response()->json($data);
        }
    }

    public function listClassSectionFeeperiod(Request $request)
    {
        $branch_class = BranchClass::where('branch_id', $request->id)->with(['com_classes'])->get()->pluck('com_classes');

        $data = [
            'fee_periods' => FeePeriod::where('branch_id', $request->id)->get(),
            'classes' => $branch_class
        ];

        return response()->json($data);
    }

    public function listClassSubjects(Request $request)
    {
        $subjects = listClassSubjects($request->id);

        return response()->json($subjects);
    }

    public function getClassSubjects(Request $request, $branch_id)
    {
        //in load select, mostly places we're using branchClass ID, so made this function by using branch class ID
        $branch_class = BranchClass::find($request->id);
        $class_id = ! empty($branch_class) ? $branch_class->class_id : 0;
        $subjects = getClassSubjects($class_id, $branch_id);

        return response()->json($subjects);
    }

    public function getClassSubjectsByClassID(Request $request)
    {
        $subjects = getClassSubjects($request->id);

        return response()->json($subjects);
    }

    public function getClassSectionSubjects(Request $request, $branch_id)
    {

        if ($request->id) {
            $branch_class = BranchClass::find($request->id);
            $class_id = ! empty($branch_class) ? $branch_class->class_id : 0;
            $subjects = getClassSubjects($class_id, $branch_id);
            $sections = BranchClassSection::where(['branch_id' => $branch_class->branch_id, 'class_id' => $branch_class->class_id])->with(['sections'])->get();

            $data = [
                'sections' => $sections,
                'subjects' => $subjects
            ];

            return response()->json($data);
        }
    }

    public function listClassSections(Request $request)
    {
        //dd($request->all());
        $data = BranchClassSection::where('branch_id', $request->branch_id)->where('class_id', $request->id)->with('sections')->get();
        //dd($data->toArray());
        return response()->json($data);
    }

    public function listBranchTerms(Request $request)
    {
        $data = Term::where('branch_id', $request->id)->get();

        return response()->json($data);
    }

    public function listTermWeeks(Request $request)
    {
        $term = Term::where('id', $request->id)->first();
        $end_date = Carbon::parse($term->end_date)->format('Y-m-d');
        $difference = Carbon::parse($term->start_date)->diffInWeeks($end_date);

        $weeks = Week::whereIn('id', range(1, $difference))->get();

        return response()->json($weeks);
    }

    public function listStudents(Request $request)
    {

        if (isset($request->sections)) {
            $student_ids = ClassStudent::where('academic_year_id', $request->academic_year_id[0])->where('is_valid', 1)->whereIn('branch_class_section_id', $request->sections)
                //Remove left students
                ->whereHas('students', function ($query) {
                    $query->where('status', '!=', 'left');
                })
                ->get()->pluck('students.id');
        } else if (! isset($request->sections) && isset($request->classes)) {
            $student_ids = ClassStudent::where('academic_year_id', $request->academic_year_id[0])->where('is_valid', 1)->whereHas('branch_class_sections', function ($query) use ($request) {
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
            $student_ids = ClassStudent::where('academic_year_id', $request->academic_year_id[0])->where('is_valid', 1)->whereHas('branch_class_sections', function ($query) use ($request) {
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
            if (! empty($student_id)) {
                $student_active_class = Student::where('id', $student_id)->with('active_class.branch_class_sections')->first();

                if ($student_active_class) {
                    // if ($student_active_class->active_class->is_promoted == null || $student_active_class->active_class->is_promoted == 0) {
                    //     $student = Student::where('id', $student_id)->whereHas('student_invoices', function ($query) use ($student_active_class) {
                    //         $query->where(['is_paid' => 1, 'invoice_frequency' => 'Admission'])->whereHas('student_fee_package', function ($query) use ($student_active_class) {
                    //             $query->where([
                    //                 'academic_year_id' => $student_active_class->active_class->academic_year_id,
                    //                 'com_class_id' => $student_active_class->active_class->branch_class_sections->com_classes->id,
                    //                 'section_id' => $student_active_class->active_class->branch_class_sections->sections->id
                    //             ]);
                    //         });
                    //     })->with(['active_class.branch_class_sections.sections', 'active_class.branch_class_sections.com_classes'])->first();
                    // } else {
                        $student = Student::where('id', $student_id)
                        // ->whereHas('student_invoices', function ($query) use ($student_active_class) {
                        //     $query->whereHas('student_fee_package', function ($query) use ($student_active_class) {
                        //         $query->where([
                        //             'academic_year_id' => $student_active_class->active_class->academic_year_id,
                        //             'com_class_id' => $student_active_class->active_class->branch_class_sections->com_classes->id,
                        //             'section_id' => $student_active_class->active_class->branch_class_sections->sections->id
                        //         ]);
                        //     });
                        // })
                        ->with(['active_class.branch_class_sections.sections', 'active_class.branch_class_sections.com_classes'])->first();
                    // }
                }

                if (isset($student)) {
                    $students = array_merge($students, [$student->toArray()]);
                }
            }
        }

        if (isset($request->calling_from) && $request->calling_from == 'assessment_entry') {
            $student_list_data['html'] = view('assessment.assessment_entry.student_list_tr', ['students' => $students])->render();
            return response()->json($student_list_data);
        } else if (isset($request->calling_from) && $request->calling_from == 'skill_behaviour_entry') {
            $student_list_data['html'] = view('assessment.skill_behaviour.student_list_tr', ['students' => $students, 'show_skill_btn' => $request->show_skill_btn])->render();
            return response()->json($student_list_data);
        } elseif (isset($request->calling_from) && $request->calling_from == 'subject_remarks') {
            $student_list_data['html'] = view('assessment.subject_remarks.student_list_tr', ['students' => $students, 'show_skill_btn' => $request->show_skill_btn])->render();
            return response()->json($student_list_data);
        }
        return DataTables::of($students)
            ->addIndexColumn()
            ->addColumn('full_name', function ($row) {
                return view('students.student_image_tr', ['row' => $row]);
            })
            ->addColumn('roll_no', function ($row) {
                return $row['roll_no'];
            })
            ->addColumn('invoice_status', function ($row) use ($request) {
                $checkInvoice = StudentInvoice::where(['student_id' => $row['id'], 'fee_period_id' => $request['filters']['feePeriodInput']])->where('bank_payment_status', '!=', 'cancelled')->first();
                if (isset($checkInvoice)) {
                    return '<span class="badge bg-danger">Already Generated</span>';
                }
                return '<span class="badge bg-primary">No Invoice</span>';
            })
            ->addColumn('invoice_no', function ($row) use ($request) {
                $checkInvoice = StudentInvoice::where(['student_id' => $row['id'], 'fee_period_id' => $request['filters']['feePeriodInput']])->where('bank_payment_status', '!=', 'cancelled')->first();
                if (isset($checkInvoice)) {
                    return view('students.invoice_list_link', ['row' => $checkInvoice]);
                }
                return '-';
            })
            ->addColumn('action', function ($row) use ($request) {
                $check_disable = true;
                $checkInvoice = StudentInvoice::where(['student_id' => $row['id'], 'fee_period_id' => $request['filters']['feePeriodInput']])->where('bank_payment_status', '!=', 'cancelled')->first();
                if (isset($checkInvoice)) {
                    $check_disable = false;
                }

                return view('students.bulk_invoices.bulk_students_action', ['row' => $row, 'check_disable', $check_disable]);
            })
            ->rawColumns(['full_name', 'invoice_status', 'action'])
            ->make(true);
    }

    public function listInvoices(Request $request)
    {
        // dd($request->sections);
        if (isset($request->sections)) {
            $student_ids = ClassStudent::where('is_valid', 1)->whereIn('branch_class_section_id', $request->sections)->get()->pluck('students.id');
        } else if (! isset($request->sections) && isset($request->classes)) {
            $student_ids = ClassStudent::where('is_valid', 1)->whereHas('branch_class_sections', function ($query) use ($request) {
                $query->whereHas('com_classes', function ($subquery) use ($request) {
                    $subquery->whereIn('branch_id', $request->branches)->whereIn('class_id', $request->classes);
                });
            })->get()->pluck('students.id');
        } else {
            $student_ids = ClassStudent::where('is_valid', 1)->whereHas('branch_class_sections', function ($query) use ($request) {
                $query->whereHas('com_classes', function ($subquery) use ($request) {
                    $subquery->whereIn('branch_id', $request->branches);
                });
            })->get()->pluck('students.id');
        }

        $invoices = [];
        foreach ($student_ids as $student_id) {
            if (! empty($student_id)) {
                $student_active_class = Student::where('id', $student_id)->with('active_class.branch_class_sections')->first();

                $whereClause = [
                    'student_id' => $student_id,
                    'invoice_frequency' => 'Monthly'
                ];

                if (isset($request['filters']['feePeriod'])) {
                    $whereClause['fee_period_id'] = (int) $request['filters']['feePeriod'];
                }

                $invoice = StudentInvoice::where($whereClause)->with([
                    'student.active_class',
                    'student_fee_package.fee_package',
                    'student_fee_package.fee_package.fee_package_type',
                    'student_fee_package.fee_concession.fee_concession_type',
                    'student_fee_package.academic_year',
                    'student_fee_package.com_class',
                    'student_fee_package.section',
                    'student_invoice_items.fee_charges.fee_charges_type',
                    'invoice_type',
                    'payment_source',
                    'promo.promo_type',
                    'fee_period'
                ])->whereHas('student_fee_package', function ($query) use ($student_active_class) {
                    $query->where([
                        'academic_year_id' => $student_active_class->active_class ? $student_active_class->active_class->academic_year_id : 0,
                        'com_class_id' => $student_active_class->active_class ? $student_active_class->active_class->branch_class_sections->com_classes->id : 0,
                        'section_id' => $student_active_class->active_class ? $student_active_class->active_class->branch_class_sections->sections->id : 0
                    ]);
                })->latest('created_at')->first();
                if (isset($invoice)) {
                    $invoices = array_merge($invoices, [$invoice->toArray()]);
                }
            }
        }

        // dd($invoices);
        return DataTables::of($invoices)
            ->addColumn('invoice_no', function ($row) {
                return view('students.invoice_list_link', ['row' => $row]);
            })
            ->addColumn('full_name', function ($row) {
                return view('students.student_image_tr', ['row' => $row['student']]);
            })
            ->addColumn('fee_period_range', function ($row) {
                $value = $row['fee_period']['from_date'] . ' / ' . $row['fee_period']['to_date'];
                return $value;
            })
            ->addColumn('concessions_percent', function ($row) {
                $value = isset($row['student_fee_package']['fee_concession']) ? $row['student_fee_package']['fee_concession']['concession_percentage'] : '-';
                return $value;
            })
            ->addColumn('concessions_type', function ($row) {
                $value = isset($row['student_fee_package']['fee_concession']) ? $row['student_fee_package']['fee_concession']['fee_concession_type']['name'] : '-';
                return $value;
            })
            ->addColumn('is_paid', function ($row) {
                $badge = $row['is_paid'] == 1 ? '<span class="badge bg-primary">Paid</span>' : '<span class="badge bg-danger">Unpaid</span>';
                return $badge;
            })
            // ->addColumn('total', function ($row) {
            //     $data = calculate_total_price_by_invoice($row);
            //     // dump($data);
            //     return number_format($data['total']);
            // })
            ->addColumn('checkbox', function ($row) {
                return view('students.bulk_invoices.bulk_invoices_action', ['row' => $row]);
            })
            ->addColumn('action', function ($row) {
                return view('students.invoice_actions', ['row' => $row]);
            })
            ->rawColumns(['action', 'is_paid', 'concessions_percent', 'concessions_type', 'invoice_no'])
            ->make(true);
    }

    //For NWA 07/21/2023
    public function NWAlistInvoices(Request $request)
    {
        ini_set('max_execution_time', 180);
        $academic_year = AcademicYear::where('active', 1)->first();
        if ($request->academic_year_id != $academic_year->id) {
            if (isset($request->sections)) {
                $student_ids = ClassStudent::where('academic_year_id', $request->academic_year_id)->whereIn('branch_class_section_id', $request->sections)->get()->pluck('students.id');
            } elseif (! isset($request->sections) && isset($request->classes)) {
                $student_ids = ClassStudent::where('academic_year_id', $request->academic_year_id)->whereHas('branch_class_sections', function ($query) use ($request) {
                    $query->whereHas('com_classes', function ($subquery) use ($request) {
                        $subquery->whereIn('branch_id', $request->branches)->whereIn('class_id', $request->classes);
                    });
                })->get()->pluck('students.id');
            } else {
                $student_ids = ClassStudent::where('academic_year_id', $request->academic_year_id)->whereHas('branch_class_sections', function ($query) use ($request) {
                    $query->whereHas('com_classes', function ($subquery) use ($request) {
                        $subquery->whereIn('branch_id', $request->branches);
                    });
                })->get()->pluck('students.id');
            }
            //dd($student_ids->toArray());
            $invoices = [];
            foreach ($student_ids as $student_id) {
                if (! empty($student_id)) {
                    $student_active_class = Student::where('id', $student_id)->with('invoice_active_class.branch_class_sections')->first();
                    //dd($student_active_class->toArray());
                    $whereClause = [
                        'student_id' => $student_id,
                        'invoice_frequency' => 'Monthly'
                    ];

                    if (isset($request['filters']['feePeriod'])) {
                        $whereClause['fee_period_id'] = (int) $request['filters']['feePeriod'];
                    }
                    $invoice = StudentInvoice::where([
                        'student_id' => $student_id,
                        'invoice_frequency' => 'Admission',
                        'fee_period_id' => $request['filters']['feePeriod']
                    ])->with([
                        'student.invoice_active_class',
                        'student_fee_package.fee_package',
                        'student_fee_package.fee_package.fee_package_type',
                        'student_fee_package.fee_concession.fee_concession_type',
                        'student_fee_package.academic_year',
                        'student_fee_package.com_class',
                        'student_fee_package.section',
                        'student_invoice_items.fee_charges.fee_charges_type',
                        'invoice_type',
                        'payment_source',
                        'promo.promo_type',
                        'fee_period'
                    ])->whereHas('student_fee_package', function ($query) use ($student_active_class, $request) {
                        $query->where([
                            'academic_year_id' => $request->academic_year_id,
                            'com_class_id' => $student_active_class->invoice_active_class ? $student_active_class->invoice_active_class->branch_class_sections->com_classes->id : 0,
                            'section_id' => $student_active_class->invoice_active_class ? $student_active_class->invoice_active_class->branch_class_sections->sections->id : 0
                        ]);
                    })->latest('created_at')->first();
                    //    dd($invoice->toArray());
                    // $invoice = StudentInvoice::where($whereClause)->with([
                    //     //'student.invoice_active_class',
                    //     'student_fee_package.fee_package',
                    //     'student_fee_package.fee_package.fee_package_type',
                    //     'student_fee_package.fee_concession.fee_concession_type',
                    //     'student_fee_package.academic_year',
                    //     'student_fee_package.com_class',
                    //     'student_fee_package.section',
                    //     'student_invoice_items.fee_charges.fee_charges_type',
                    //     'invoice_type',
                    //     'payment_source',
                    //     'promo.promo_type',
                    //     'fee_period'
                    // ])->whereHas('student_fee_package', function ($query) use ($student_active_class,$request) {
                    //     $query->where([
                    //         'academic_year_id' => $request->academic_year_id,
                    //         'com_class_id' => $student_active_class->invoice_active_class ? $student_active_class->invoice_active_class->branch_class_sections->com_classes->id : 0,
                    //         'section_id' => $student_active_class->invoice_active_class ? $student_active_class->invoice_active_class->branch_class_sections->sections->id : 0
                    //     ]);
                    // })->latest('created_at')->first(); dd($invoice->toArray());
                    if (isset($invoice)) {
                        $invoices = array_merge($invoices, [$invoice->toArray()]);
                    }
                }
            }
        } else {
            if (isset($request->sections)) {
                $student_ids = ClassStudent::where('is_valid', 1)->whereIn('branch_class_section_id', $request->sections)->get()->pluck('students.id');
            } else if (! isset($request->sections) && isset($request->classes)) {
                $student_ids = ClassStudent::where('is_valid', 1)->whereHas('branch_class_sections', function ($query) use ($request) {
                    $query->whereHas('com_classes', function ($subquery) use ($request) {
                        $subquery->whereIn('branch_id', $request->branches)->whereIn('class_id', $request->classes);
                    });
                })->get()->pluck('students.id');
            } else {
                $student_ids = ClassStudent::where('is_valid', 1)->whereHas('branch_class_sections', function ($query) use ($request) {
                    $query->whereHas('com_classes', function ($subquery) use ($request) {
                        $subquery->whereIn('branch_id', $request->branches);
                    });
                })->get()->pluck('students.id');
            }

            $invoices = [];
            foreach ($student_ids as $student_id) {
                if (! empty($student_id)) {
                    $student_active_class = Student::where('id', $student_id)->with('active_class.branch_class_sections')->first();

                    $whereClause = [
                        'student_id' => $student_id,
                        'invoice_frequency' => 'Monthly'
                    ];

                    if (isset($request['filters']['feePeriod'])) {
                        $whereClause['fee_period_id'] = (int) $request['filters']['feePeriod'];
                    }

                    $invoice = StudentInvoice::where($whereClause)->with([
                        'student.active_class',
                        'student_fee_package.fee_package',
                        'student_fee_package.fee_package.fee_package_type',
                        'student_fee_package.fee_concession.fee_concession_type',
                        'student_fee_package.academic_year',
                        'student_fee_package.com_class',
                        'student_fee_package.section',
                        'student_invoice_items.fee_charges.fee_charges_type',
                        'invoice_type',
                        'payment_source',
                        'promo.promo_type',
                        'fee_period'
                    ])->whereHas('student_fee_package', function ($query) use ($student_active_class) {
                        $query->where([
                            'academic_year_id' => $student_active_class->active_class ? $student_active_class->active_class->academic_year_id : 0,
                            'com_class_id' => $student_active_class->active_class ? $student_active_class->active_class->branch_class_sections->com_classes->id : 0,
                            'section_id' => $student_active_class->active_class ? $student_active_class->active_class->branch_class_sections->sections->id : 0
                        ]);
                    })->latest('created_at')->first();
                    if (isset($invoice)) {
                        $invoices = array_merge($invoices, [$invoice->toArray()]);
                    }
                }
            }
        }

        // dd($invoices);
        return DataTables::of($invoices)
            ->addColumn('invoice_no', function ($row) {
                return view('students.invoice_list_link', ['row' => $row]);
            })
            ->addColumn('full_name', function ($row) {
                return view('students.student_image_tr', ['row' => $row['student']]);
            })
            ->addColumn('fee_period_range', function ($row) {
                $value = $row['fee_period']['from_date'] . ' / ' . $row['fee_period']['to_date'];
                return $value;
            })
            ->addColumn('concessions_percent', function ($row) {
                $value = isset($row['student_fee_package']['fee_concession']) ? $row['student_fee_package']['fee_concession']['concession_percentage'] : '-';
                return $value;
            })
            ->addColumn('concessions_type', function ($row) {
                $value = isset($row['student_fee_package']['fee_concession']) ? $row['student_fee_package']['fee_concession']['fee_concession_type']['name'] : '-';
                return $value;
            })
            ->addColumn('is_paid', function ($row) {
                $badge = $row['is_paid'] == 1 ? '<span class="badge bg-primary">Paid</span>' : '<span class="badge bg-danger">Unpaid</span>';
                return $badge;
            })
            // ->addColumn('total', function ($row) {
            //     $data = calculate_total_price_by_invoice($row);
            //     // dump($data);
            //     return number_format($data['total']);
            // })
            ->addColumn('checkbox', function ($row) {
                return view('students.bulk_invoices.bulk_invoices_action', ['row' => $row]);
            })
            ->addColumn('action', function ($row) {
                return view('students.invoice_actions', ['row' => $row]);
            })
            ->rawColumns(['action', 'is_paid', 'concessions_percent', 'concessions_type', 'invoice_no'])
            ->make(true);
    }

    public function getFeePeriod(Request $request)
    {
        $fee_period = FeePeriod::where('id', $request->id)->first();
        return response()->json($fee_period);
    }

    public function getFeePackagePeriod(Request $request)
    {

        $fee_packages = FeePackage::where(['branch_id' => get_branch_id(), 'academic_year_id' => $request->academic_year_id])->whereHas('fee_package_type', function ($query) {
            $query->where('name', 'Monthly');
        })->get();
        $fee_periods = FeePeriod::where(['branch_id' => get_branch_id(), 'academic_year_id' => $request->academic_year_id])->get();
        //dd($fee_packages.$fee_periods);

        $data = [
            'fee_packages' => $fee_packages,
            'fee_periods' => $fee_periods
        ];

        return response()->json($data);
    }

    function getFeePeriodByYear(Request $request)
    {
        $fee_periods = FeePeriod::where(['branch_id' => get_branch_id(), 'academic_year_id' => $request->academic_year_id])->get();

        $data = [
            'fee_periods' => $fee_periods
        ];
        return response()->json($data);
    }

    function getFeePeriodsByBranch(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'branch_id' => 'required|exists:branches,id'
        ]);

        $fee_periods = FeePeriod::where([
            'branch_id' => $request->branch_id,
            'academic_year_id' => $request->academic_year_id
        ])->get();

        $data = [
            'fee_periods' => $fee_periods
        ];
        return response()->json($data);
    }

    public function getAssessmentLevelChild(Request $request)
    {
        $assessment_level = AssessmentLevel::where('parent_id', $request->id)->get();
        return response()->json($assessment_level);
    }

    public function getStudentById(Request $request)
    {
        $data['student'] = $student = Student::where('roll_no', $request->id)->with(
            'active_class.branch_class_sections.com_classes',
            'active_class.branch_class_sections.sections',
            'active_class.academic_years',
            'branch'
        )->first();
        if ($data['student']->security_amount == null) {
            $data['student']->security_amount = get_student_security($data['student']->branch_id, $data['student']->admission_year_id);
        }
        $lastPaidInvoice = StudentInvoice::with([
            'student_fee_package.fee_package',
            'student_fee_package.fee_package.fee_package_type',
            'student_fee_package.fee_concession.fee_concession_type',
            'student_fee_package.academic_year',
            'student_fee_package.com_class',
            'student_fee_package.section',
            'student_invoice_items.fee_charges.fee_charges_type',
            'invoice_type',
            'payment_source',
            'promo.promo_type',
            'fee_period'
        ])
            ->where(['student_id' => $student->id, 'is_paid' => 1, 'bank_payment_status' => 'paid'])
            ->latest('created_at')
            ->first();

        $lastUnpaidInvoice = StudentInvoice::with([
            'student_fee_package.fee_package',
            'student_fee_package.fee_package.fee_package_type',
            'student_fee_package.fee_concession.fee_concession_type',
            'student_fee_package.academic_year',
            'student_fee_package.com_class',
            'student_fee_package.section',
            'student_invoice_items.fee_charges.fee_charges_type',
            'invoice_type',
            'payment_source',
            'promo.promo_type',
            'fee_period'
        ])
            ->where(['student_id' => $student->id, 'is_paid' => 0, 'bank_payment_status' => 'unpaid'])
            ->latest('created_at')
            ->first();

        $data['last_paid_invoice']['total'] = $data['last_paid_invoice']['validity'] = $data['last_paid_invoice']['period'] = '';
        $data['last_unpaid_invoice']['total'] = $data['last_unpaid_invoice']['validity'] = $data['last_unpaid_invoice']['period'] = '';

        //If record exists
        if ($lastPaidInvoice) {
            //Calculate paid total
            if (get_month_name($lastPaidInvoice->fee_period->from_date) == 'February') {
                //if month is february
                //This is the formula for calculation of total
                $lastPaidTotalArray = calculate_total_price_by_invoice_index($lastPaidInvoice);
            } else {
                $lastPaidTotalArray = calculate_total_price_by_invoice($lastPaidInvoice);
            }
            // $lastPaidTotalArray = calculate_total_price_by_invoice($lastPaidInvoice);

            $month = 'N/A';

            if ($lastPaidInvoice->fee_period != null) {
                if (get_month_name($lastPaidInvoice->fee_period->from_date) == 'February') {
                    // dump(get_month_diff($lastPaidInvoice->fee_period->from_date, $lastPaidInvoice->fee_period->to_date));
                    $month = 'February';
                } else {
                    $month = get_month_diff($lastPaidInvoice->fee_period->from_date, $lastPaidInvoice->fee_period->to_date) == 1 ? get_month_name($lastPaidInvoice->fee_period->from_date) : get_month_name($lastPaidInvoice->fee_period->from_date) . ' - ' . get_month_name($lastPaidInvoice->fee_period->to_date);
                }
            }

            //echo '<pre>';print_r($lastPaidTotalArray);
            $data['last_paid_invoice']['total'] = number_format($lastPaidTotalArray['total']);
            $data['last_paid_invoice']['validity'] = date('d-m-Y', strtotime($lastPaidInvoice->paid_date));
            $data['last_paid_invoice']['period'] = $month;
        }

        //If record exists
        if ($lastUnpaidInvoice) {
            //Calculate un-paid total
            if (get_month_name($lastUnpaidInvoice->fee_period->from_date) == 'February') {
                //if month is february
                //This is the formula for calculation of total
                $lastUnPaidTotalArray = calculate_total_price_by_invoice_index($lastUnpaidInvoice);
            } else {
                $lastUnPaidTotalArray = calculate_total_price_by_invoice($lastUnpaidInvoice);
            }
            // $lastUnPaidTotalArray = calculate_total_price_by_invoice($lastUnpaidInvoice);
            $month = 'N/A';
            if ($lastUnpaidInvoice->fee_period != null) {
                if (get_month_name($lastUnpaidInvoice->fee_period->from_date) == 'February') {
                    // dump(get_month_diff($lastUnpaidInvoice->fee_period->from_date, $lastUnpaidInvoice->fee_period->to_date));
                    $month = 'February';
                } else {
                    $month = get_month_diff($lastUnpaidInvoice->fee_period->from_date, $lastUnpaidInvoice->fee_period->to_date) == 1 ? get_month_name($lastUnpaidInvoice->fee_period->from_date) : get_month_name($lastUnpaidInvoice->fee_period->from_date) . ' - ' . get_month_name($lastUnpaidInvoice->fee_period->to_date);
                }
            }
            //else $month = 'N/A';

            $data['last_unpaid_invoice']['total'] = number_format($lastUnPaidTotalArray['total']);
            $data['last_unpaid_invoice']['validity'] = date('d-m-Y', strtotime($lastUnpaidInvoice->validity_date));
            $data['last_unpaid_invoice']['period'] = $month;
        }

        //Calculate refundable security amount
        $studentInvoices = StudentInvoice::where(
            'student_id',
            $student->id
        )->with([
            'student',
            'student_fee_package.fee_package',
            'student_fee_package.fee_package.fee_package_type',
            'student_fee_package.fee_concession.fee_concession_type',
            'student_fee_package.academic_year',
            'student_fee_package.com_class',
            'student_fee_package.section',
            'student_invoice_items.fee_charges.fee_charges_type',
            'invoice_type',
            'payment_source',
            'promo.promo_type',
            'fee_period'
        ])->get();

        $securityFees = 0;
        foreach ($studentInvoices as $invoice) {
            $feesCalc = calculate_total_price_by_invoice($invoice);
            $securityFees += $feesCalc['invoices_charges']['SD'];
        }
        $data['security_amount'] = number_format($securityFees);

        //dd($lastUnpaidInvoice);

        if (empty($data['student'])) {
            return response()->json(['status' => 404, 'error' => 'No student found!']);
        }
        return response()->json($data);
    }

    public function getStudentRelationById(Request $request)
    {
        $studentId = $request->student_id;
        $relationId = $request->relation_id;

        $studentGuardian = Guardian::where('student_id', $studentId)->where('relation_id', $relationId)->first();

        if (empty($studentGuardian)) {
            return response()->json(['status' => 404, 'error' => 'No guardian found!']);
        }
        return response()->json($studentGuardian);
    }

    public function testPDF()
    {
        $pdf = Pdf::loadView('pdf.report');
        return $pdf->download('report.pdf');
    }
    public function showKgProgressReport()
    {
        return view('reports.student_progress_reports.progress_report_kg');
    }
    public function showUpperPrimaryProgressReport()
    {
        return view('reports.student_progress_reports.progress_report_upper_primary');
    }
    public function showLowerPrimaryProgressReport()
    {
        return view('reports.student_progress_reports.progress_report_lower_primary');
    }
    public function showMiddleSchoolProgressReport()
    {
        return view('reports.student_progress_reports.progress_report_middle_school');
    }
    public function showNurseryProgressReport()
    {
        return view('reports.student_progress_reports.progress_report_nursery');
    }
    public function showPreNurseryProgressReport()
    {
        return view('reports.student_progress_reports.progress_report_pre_nursery');
    }
    /**
     * @param $student_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function showProgressReport($student_id)
    {
        if (! is_null($student_id)) {
            $assessment_entries_subject_wise = AssessmentEntry::get()->groupBy('subject_id')->whereNotNull('assessment_level_three_id');
            $assessment_level = 'assessment_level_three_id';

            if ($assessment_entries_subject_wise->isEmpty()) {
                $assessment_entries_subject_wise = AssessmentEntry::get()->groupBy('subject_id')->whereNull('assessment_level_three_id');
                $assessment_level = 'assessment_level_two_id';
            }

            $assessment_names = array();
            $assessment_weightage = array();
            $assessment_data = array();

            foreach ($assessment_entries_subject_wise as $single_assessment_entry) {
                $single_subject_assessments_wise = $single_assessment_entry->groupBy($assessment_level);
                foreach ($single_subject_assessments_wise as $all_assessment_type => $assessment) {
                    if (is_null($assessment->first()->assessment_level_three_id)) {
                        $assessment_weightage[$assessment->first()->assessment_level_two->name] = $assessment->first()->assessment_weightage;
                    } else {
                        $assessment_weightage[$assessment->first()->assessment_level_three->name] = $assessment->first()->assessment_weightage;
                    }
                    $assessment_data[] = $this->calculateMarksPercentage($student_id, $assessment);
                }
            }

            $all_assessment_data = $this->removeDuplicateAssessment($assessment_data);
            //            dd($all_assessment_data, $assessment_names, $assessment_weightage);
            return view('students.progress_reports.term1_progress_reports', compact('all_assessment_data', 'assessment_weightage'));
        }

        abort(404);
    }

    /**
     * @param $student_id
     * @param $assessment_entries
     * @return array
     */
    protected function calculateMarksPercentage($student_id, $assessment_entries): array
    {
        $assessment_obtained_marks = 0;

        foreach ($assessment_entries as $assessment) {
            $assessment_obtained_marks += (int)$assessment->student_assessment_marks->where('student_id', $student_id)->sum('obtained_marks_grades');
        }

        $assessment_total_marks = $assessment_entries->sum('grade_marks');
        $assessment_weightage = $assessment_entries->first()->assessment_weightage;
        $data['subject_name'] = $assessment_entries->first()->subject->subject_name;
        $data[$assessment_entries->first()->assessment_level_two->name] = round(($assessment_obtained_marks / $assessment_total_marks) * $assessment_weightage);

        return $data;
    }

    /**
     * @param $assessment_data
     * @return array
     */
    protected function removeDuplicateAssessment($assessment_data): array
    {
        $single_subject_assessments_marks = array();
        $all_subject_assessments_data = array();
        $next_ele = $previous_ele = null;
        $j = 0;
        foreach ($assessment_data as $key => $assessment_marks) {
            if (! is_null($previous_ele) && $previous_ele['subject_name'] == $assessment_marks['subject_name']) {
                unset($assessment_data[$key - 1]);
                ++$j;
                $single_subject_assessments_marks = $previous_ele;

                foreach ($assessment_marks as $assessment => $marks) {
                    $single_subject_assessments_marks[$assessment] = $marks;
                }
                $previous_ele = $single_subject_assessments_marks;
                $all_subject_assessments_data[] = $single_subject_assessments_marks;
                if ($j >= 2) {
                    array_splice($all_subject_assessments_data, 0, 1);
                }

                unset($assessment_data[$key]);
                continue;
            }
            $previous_ele = $assessment_marks;
            $j = 0;
        }
        foreach ($assessment_data as $assessment) {
            $all_subject_assessments_data[] = $assessment;
        }
        return $all_subject_assessments_data;
    }
}
