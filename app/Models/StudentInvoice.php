<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use Session;
use Illuminate\Support\Str;

/**
 * @method static select(string $string)
 */
class StudentInvoice extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;

    protected $fillable = [
        'student_fee_package_id',
        'student_id',
        'promo_id',
        'invoice_type_id',
        'invoice_frequency',
        'payment_source_id',
        'invoice_no',
        'is_paid',
        'paid_date',
        'due_date',
        'issue_date',
        'validity_date',
        'fee_month',
        'fee_period_id',
        'royalty_percentage',
        'royalty_amount',
        'bank_payment_status',
        'bank_received_amount',
        'paid_amount',
        'is_adjusted',
        'adjusted_invoice_id',
        'remarks',
        'due_date_fine',
        'arrears_fine',
        'subtotal',
        'total_discount',
        'total_payable',
    ];
    protected $dates = [
        'paid_date',
        'due_date',
        'issue_date',
        'validity_date',
    ];

    protected static function boot()
    {

        parent::boot();

        static::creating(function ($model) {
            // Try to get student_id from request first (for single invoice creation)
            $studentId = request()->student_id;

            // If not in request, try to get from model attributes (for bulk creation)
            if (! $studentId && isset($model->attributes['student_id'])) {
                $studentId = $model->attributes['student_id'];
            }

            if (! $studentId) {
                Log::error('No student_id found in StudentInvoice creation', [
                    'request_student_id' => request()->student_id,
                    'model_student_id' => $model->attributes['student_id'] ?? null
                ]);
                return;
            }

            $student = Student::find($studentId);

            // Check if student exists and has branch_id
            if (! $student || ! $student->branch_id) {
                Log::error('Student not found or missing branch_id in StudentInvoice creation', [
                    'student_id' => $studentId,
                    'student' => $student ? $student->toArray() : null
                ]);
                return;
            }

            $branch = Branch::find($student->branch_id);

            // Check if branch exists
            if (! $branch) {
                Log::error('Branch not found for student in StudentInvoice creation', [
                    'student_id' => $student->id,
                    'branch_id' => $student->branch_id
                ]);
                return;
            }

            if (! $model->isDirty('invoice_no')) {
                $first_env_no = $branch->branch_code . '0000000001';

                // if ($student->from_branch == null)
                //     $last_inv_no = $model->whereHas('student', function ($query) use ($branch) {
                //         $query->where('branch_id', $branch->id);
                //     })->max('invoice_no');
                // else
                $last_inv_no = $model->where('invoice_no', 'like', $branch->branch_code . '%')->max('invoice_no');
                // dd($last_inv_no);
                $model->invoice_no = isset($last_inv_no) ? $last_inv_no + 1 : $first_env_no;
            }

            if (! $model->isDirty('royalty_percentage')) {
                $model->royalty_percentage = get_branch_royalty($branch->id);
            }
        });
    }

    public static function apply_monthly_package($student_id)
    {
        $current_student_fee_package = StudentFeePackage::where(['student_id' => $student_id, 'is_valid' => 1])->first();

        if (! $current_student_fee_package) {
            \Log::error('No valid fee package found for student', ['student_id' => $student_id]);
            return false;
        }

        // Get the student's branch
        $student = Student::find($student_id);
        if (! $student) {
            \Log::error('Student not found', ['student_id' => $student_id]);
            return false;
        }

        // Get the active academic year for this specific branch using the existing helper function
        $branch_academic_year = get_current_acad_year_by_branch_id($student->branch_id);

        if (! $branch_academic_year) {
            \Log::error('No active academic year found for branch', [
                'student_id' => $student_id,
                'branch_id' => $student->branch_id
            ]);
            return false;
        }

        // Deactivate the current fee package
        $current_student_fee_package->update(['is_valid' => 0, 'active_till' => Carbon::now()]);

        // Find monthly fee package for the current branch and active academic year
        $fee_package = FeePackage::where('branch_id', $student->branch_id)
            ->where('academic_year_id', $branch_academic_year->academic_year_id)
            ->whereHas('fee_package_type', function ($q) {
                $q->where('name', 'Monthly');
            })->first();

        if (! $fee_package) {
            \Log::error('No monthly fee package found for branch and academic year', [
                'branch_id' => $student->branch_id,
                'academic_year_id' => $branch_academic_year->academic_year_id,
                'branch_academic_year_id' => $branch_academic_year->id
            ]);
            return false;
        }

        $input = [
            "fee_package_id" => $fee_package->id,
            "fee_concession_id" => $current_student_fee_package->fee_concession_id,
            "academic_year_id" => $branch_academic_year->academic_year_id, // Use branch's active academic year
            "com_class_id" => $current_student_fee_package->com_class_id,
            "section_id" => $current_student_fee_package->section_id,
            "student_id" => $student_id
        ];

        $new_fee_package = StudentFeePackage::create($input);

        \Log::info('Monthly package applied successfully', [
            'student_id' => $student_id,
            'branch_id' => $student->branch_id,
            'old_academic_year_id' => $current_student_fee_package->academic_year_id,
            'new_academic_year_id' => $branch_academic_year->academic_year_id,
            'branch_academic_year_id' => $branch_academic_year->id,
            'new_fee_package_id' => $new_fee_package->id
        ]);

        return $new_fee_package;
    }

    public static function paid_unpaid_invoices_report($report_for, $with_get = 0)
    {
        session()->forget('paid_data');

        if ($report_for == 'unpaid') {
            $where_clause_arr = ['is_paid' => 0, 'bank_payment_status' => 'unpaid'];
        } elseif ($report_for == 'paid') {
            $where_clause_arr = ['is_paid' => 1, 'bank_payment_status' => 'paid'];
        }
        $branch_id = 0;
        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
        }

        $data = self::where($where_clause_arr);

        $data = $data->whereHas('student', function ($q) use ($branch_id, $report_for) {
            if ($branch_id) {
                $q->where('branch_id', $branch_id);
            }
            if ($report_for == 'unpaid') {
                $q->whereNull('deleted_at');
            }
        });
        // dd($data);
        $data = $data->with([
            'student.state',
            'student.branch.region',
            'student.active_class.branch_class_sections.com_classes',
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
            'fee_period',
            'payments' // Add payments for arrears calculation
        ]);
        return $with_get ? $data->get() : $data;
    }

    public static function paid_unpaid_invoice_filteration($request, $data, $with_get = 0)
    {
        session()->forget('paid_data');
        if ($request->region_id) {
            $data = $data->whereHas('student.branch.region', function ($query) use ($request) {
                $query->where('id', $request->region_id);
            });
        }

        $data = $data->whereHas('student', function ($query) use ($request) {
            if ($request->branch_id) {
                $query->where('branch_id', $request->branch_id);
            }
            if ($request->state_id) {
                $query->where('state_id', $request->state_id);
            }
            if ($request->gender) {
                $query->where('gender', $request->gender);
            }
        });

        if ($request->academic_year_id) {
            $data = $data->whereHas('student_fee_package', function ($query) use ($request) {
                $query->where('academic_year_id', $request->academic_year_id);
            });
        }

        if ($request->section_id) {
            $data = $data->whereHas('student_fee_package', function ($query) use ($request) {
                $query->where('section_id', $request->section_id);
            });
        }

        if ($request->class_id) {
            $data = $data->whereHas('student.active_class.branch_class_sections', function ($query) use ($request) {
                $query->where('class_id', $request->class_id);
            });
        }

        if ($request->fee_period_id) {
            $data = $data->where('fee_period_id', $request->fee_period_id);
        }

        // Fixed search functionality
        if ($request->searchName && Str::length($request->searchName) > 2) {
            session()->forget('paid_data');
            $data = $data->where(function ($query) use ($request) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->searchName . '%')
                      ->orWhere('middle_name', 'like', '%' . $request->searchName . '%')
                      ->orWhere('last_name', 'like', '%' . $request->searchName . '%')
                      ->orWhere('registration_no', 'like', '%' . $request->searchName . '%')
                      ->orWhere('roll_no', 'like', '%' . $request->searchName . '%');
                })
                ->orWhere('invoice_no', 'like', '%' . $request->searchName . '%');
            });
        }
        // Only store in session if explicitly requested (for exports)
        if ($with_get) {
            session(['paid_data' => $data->get()]);
        }
        return $with_get ? $data->get() : $data;
    }

    public static function royaltyComputation($request)
    {
        ini_set('max_execution_time', 300);
        $invoiceFilters = [];
        if (! empty($request->filters['payment_status'])) {
            $invoiceFilters['bank_payment_status'] = $request->filters['payment_status'];
        }

        if (! empty($request->filters['fee_period_id'])) {
            $invoiceFilters['fee_period_id'] = $request->filters['fee_period_id'];
        }

        $classFilters = [];
        if (! empty($request->filters['class_id'])) {
            $classFilters['com_class_id'] = $request->filters['class_id'];
        }
        if (! empty($request->filters['section_id'])) {
            $classFilters['section_id'] = $request->filters['section_id'];
        }
        if (! empty($request->filters['academic_year_id'])) {
            $classFilters['academic_year_id'] = $request->filters['academic_year_id'];
        }

        $students = [];
        $students_invoices = StudentInvoice::where($invoiceFilters)->whereHas('student', function ($query) use ($request) {
            if (! empty($request->filters['branch_id'])) {
                $query->where('branch_id', $request->filters['branch_id']);
            }
        })->whereHas('student_fee_package', function ($query) use ($classFilters) {
            $query->where($classFilters);
        })->with([
                    'student_fee_package.fee_package.fee_packages_fee_charges.fee_charges',
                    'student.branch.class_group',
                    'student.branch.bank_accounts',
                    'student.branch.contact_information',
                    'student.city',
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
                    'student_concessions',
                    'student_ledger_invoice',
                    'fee_period',
                    'student' => function ($query) use ($request) {
                        //$query->where('branch_id', $request->filters['branch_id']);
                    }

                ]);
        if (! empty($request->filters['state_id'])) {
            $students_invoices->whereHas('student.branch.contact_information', function ($query1) use ($request) {
                $query1->where('state_id', $request->filters['state_id']);
            });
        }

        if (isset($request->filters['from_date']) && ! empty($request->filters['from_date'])) {
            $students_invoices->whereDate('paid_date', '>=', $request->filters['from_date']);
        }

        if (isset($request->filters['to_date']) && ! empty($request->filters['to_date'])) {
            $students_invoices->whereDate('paid_date', '<=', $request->filters['to_date']);
        }


        $students = $students_invoices->get();
        $initial = [
            'tution' => 0,
            'admission' => 0,
            'security' => 0,
            'total' => 0,
            'total_royalty' => 0,
            'nwa_amount' => 0,
        ];

        $total_fee_charges = array_reduce($students->toArray(), function ($prev, $invoice) {
            $fees_calc = calculate_total_price_by_invoice($invoice);

            return [
                'tution' => $prev['tution'] + $fees_calc['invoices_charges']['TF'],
                'admission' => $prev['admission'] + $fees_calc['invoices_charges']['AF'],
                'security' => $prev['security'] + $fees_calc['invoices_charges']['SD'],
                'total' => $prev['total'] + $fees_calc['total'],
                'total_royalty' => $prev['total_royalty'] + $fees_calc['royalty_amount'],
                'nwa_amount' => $prev['nwa_amount'] + $fees_calc['total_after_royalty'],
            ];
        }, $initial);

        $total_fee_charges['tution'] = number_format($total_fee_charges['tution']);
        $total_fee_charges['admission'] = number_format($total_fee_charges['admission']);
        $total_fee_charges['security'] = number_format($total_fee_charges['security']);
        $total_fee_charges['total'] = number_format($total_fee_charges['total']);
        $total_fee_charges['total_royalty'] = number_format($total_fee_charges['total_royalty']);
        $total_fee_charges['nwa_amount'] = number_format($total_fee_charges['nwa_amount']);

        $return_data['students'] = $students;
        $return_data['total_fee_charges'] = $total_fee_charges;

        return $return_data;
    }

    public function setBankPaymentStatusAttribute($value)
    {
        $this->attributes['bank_payment_status'] = strtolower($value);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id')
            ->withTrashed();
        //->where('status', '!=', 'left');
    }

    public function student_invoice_items()
    {
        return $this->hasMany(StudentInvoiceItem::class);
    }

    public function items()
    {
        return $this->hasMany(StudentInvoiceItem::class);
    }

    public function student_ledger_invoice()
    {
        return $this->hasOne(StudentLedgerInvoice::class, 'student_invoice_id', 'id');
    }

    public function student_fee_package()
    {
        return $this->belongsTo(StudentFeePackage::class, 'student_fee_package_id', 'id');
    }

    public function invoice_type()
    {
        return $this->belongsTo(InvoiceType::class, 'invoice_type_id', 'id');
    }

    public function payment_source()
    {
        return $this->belongsTo(PaymentSource::class, 'payment_source_id', 'id');
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'promo_id', 'id');
    }

    public function fee_period()
    {
        return $this->belongsTo(FeePeriod::class, 'fee_period_id', 'id');
    }

    public function student_address()
    {
        return $this->belongsTo(StudentAddress::class, 'student_id', 'student_id');
    }

    public function student_concessions()
    {
        return $this->hasMany(StudentConcession::class, 'student_id', 'student_id');
    }

    public function ledger_entry()
    {
        return $this->hasOne(StudentLedgerInvoice::class, 'student_invoice_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(StudentPayment::class, 'invoice_id', 'id');
    }

    public function arrears_carried_to()
    {
        return $this->hasMany(StudentArrearsHistory::class, 'from_invoice_id', 'id');
    }

    public function arrears_carried_from()
    {
        return $this->hasMany(StudentArrearsHistory::class, 'to_invoice_id', 'id');
    }

    public function arrears_history()
    {
        return $this->hasMany(StudentArrearsHistory::class, 'to_invoice_id', 'id');
    }

    public static function max_fee_period_id($id)
    {
        return self::where('student_id', $id)->max('fee_period_id');
    }
}
