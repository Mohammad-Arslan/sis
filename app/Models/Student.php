<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\SerializeDateTrait;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory, SerializeDateTrait, SoftDeletes, LogsActivity;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'email',
        'date_of_birth',
        'admission_wef',
        'registration_date',
        'passport_number',
        'birth_place',
        'status',
        'transfer_status',
        'system_id',
        'registration_no',
        'roll_no',
        'language_id',
        'religion_id',
        'nationality_id',
        'country_id',
        'state_id',
        'city_id',
        'branch_id',
        'security_deposit',
        'security_amount',
        'security_number',
        'from_branch',
        'previous_school_id',
        'registration_fee',
        'test_date_time',
        'interview_date_time',
        'student_image',
        'cnic',
        'emergency_phone_number',
        'admission_year_id',
        'pin_code',
        'card_no',
    ];

    protected $dates = [
        'date_of_birth',
        'admission_wef',
        'registration_date'
    ];

    protected $appends = [
        // 'studentfullname',
        'student_profile_img'
    ];

    public function getStudentProfileImgAttribute()
    {
        $image = asset('theme/src/assets/images/users/user-dummy-img.jpg');
        if (!empty($this->student_image) && get_file_from_s3('images/' . $this->student_image, $this->student_image)) {
            $image = get_file_from_s3('images/' . $this->student_image, $this->student_image);
        }
        // dd($image);

        return $image;
    }

    public static function update_student_id($student_id)
    {
        $student = self::find($student_id);
        if (isset($student->system_id))
            return;

        $branch = Branch::find($student->branch_id);

        $first_id = (int) ($branch->branch_code . '0000000001');

        $last_id = self::where('branch_id', $student->branch_id)->max('system_id');
        $new_id = isset($last_id) ? (int) ($last_id + 1) : $first_id;

        $student->update(['system_id' => (int) $new_id, 'status' => 'on_roll']);
    }

    public static function update_roll_no($student_id)
    {
        $student = self::find($student_id);
        if (isset($student->roll_no))
            return;

        $branch = Branch::find($student->branch_id);

        //$first_roll_no = '00001';
        $first_roll_no = (int) ($branch->branch_code . sprintf('%05u', $branch->student_id_from));

        $last_roll_no = self::where('branch_id', $student->branch_id)->max('roll_no');
        $new_roll_no = isset($last_roll_no) ? (int) ($last_roll_no + 1) : $first_roll_no;

        /*$formated_new_roll = $new_roll_no;
        if (strlen($new_roll_no) < 5) {
            $limit = 5 - strlen($new_roll_no);
            for ($i=0; $i < $limit; $i++) {
                $formated_new_roll = '0' . $formated_new_roll;
                dump($formated_new_roll);
            }
        }*/

        $student->update(['roll_no' => $new_roll_no]);
    }

    protected static function boot()
    {

        parent::boot();

        static::creating(function ($model) {
            // if (!$model->isDirty('branch_code')) {
            //   $count = $model->count();
            //   $newBranchCode = $count == 0 ? 7051 : $count + 7051;
            //   $checkRepitition = $model->where('branch_code', $newBranchCode)->exists();
            //   while ($checkRepitition) {
            //     $newBranchCode += 1;
            //     $checkRepitition = $model->where('branch_code', $newBranchCode)->exists();
            //   }
            //   $model->branch_code = $newBranchCode;
            // }
        });
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function student_previous_school()
    {
        return $this->belongsTo(StudentPreviousSchool::class, 'previous_school_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id', 'id');
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id', 'id');
    }

    public function student_address()
    {
        return $this->hasOne(StudentAddress::class);
    }

    public function guardian()
    {
        return $this->hasOne(Guardian::class);
    }

    public function guardians()
    {
        return $this->hasMany(Guardian::class);
    }

    public function first_guardian()
    {
        return $this->hasOne(Guardian::class, 'student_id', 'id')->oldestOfMany();
    }

    public function student_fee_package()
    {
        return $this->hasMany(StudentFeePackage::class);
    }



    public function std_fee_package()
    {
        return $this->hasOne(StudentFeePackage::class);
    }

    public function class_students()
    {
        return $this->hasMany(ClassStudent::class);
    }

    // public function studentFullName(): Attribute
    // {
    //     return new Attribute(
    //         get: fn ($value) => $this->first_name.' '.$this->middle_name.''.$this->last_name;
    //     );
    // }

    public function student_invoices()
    {
        return $this->hasMany(StudentInvoice::class, 'student_id', 'id');
    }

    public function student_monthly_invoices()
    {
        return $this->hasMany(StudentInvoice::class, 'student_id', 'id')->where('invoice_frequency', 'Monthly')->where('due_date', Carbon::now()->format('m'));
    }

    //following relation added for unpaid student report.
    public function student_invoice()
    {
        return $this->hasOne(StudentInvoice::class, 'student_id', 'id');
    }

    public function active_class()
    {
        return $this->hasOne(ClassStudent::class)->where('is_valid', 1);
    }


    //for nwa dashboard.
    public function invoice_active_class()
    {
        return $this->hasOne(ClassStudent::class);
    }

    public function general_documents()
    {
        return $this->morphMany(GeneralDocument::class, 'general_documentable');
    }

    public function employee_guardian()
    {
        return $this->hasOne(Guardian::class, 'student_id', 'id')->where('is_parent', '=', 'yes');
    }

    public function sibling_info()
    {
        return $this->hasOne(SiblingInformation::class);
    }

    public function student_attendance()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function student_behaviour_skill_marks()
    {
        return $this->hasMany(StudentBehaviourSkillMark::class, 'student_id', 'id');
    }

    public function student_behaviour_skill_remarks()
    {
        return $this->hasMany(StudentBehaviourSkillRemark::class, 'student_id', 'id');
    }

    public function admission_year()
    {
        return $this->hasOne(AcademicYear::class, 'admission_year_id', 'id');
    }

    /**
     * Scope a query to only remove students with left status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutLeft($query)
    {
        return $query->where('status', '!=', 'left');
    }

    public function student_concession()
    {
        return $this->hasOne(StudentConcession::class, 'student_id', 'id')->where('is_valid', 1)->latest();
    }

    //    public function studentFullName(): Attribute
    //    {
    //        return new Attribute(
    //            get: fn ($value) => $this->first_name.' '.$this->middle_name.''.$this->last_name;
    //        );
    //    }

    public static function attach_reg_no($student_id)
    {
        $student = self::find($student_id);
        $branch = Branch::find($student->branch_id);

        $first_reg_no = (int) ($branch->branch_code . sprintf('%06u', 1));
        $branch_code = (int) ($branch->branch_code);
        $last_reg_no = (int) self::where('branch_id', $student->branch_id)->where('registration_no', 'LIKE', '%' . $branch_code . '%')->max('registration_no');
        if ($last_reg_no == 0) {
            // dd($last_reg_no);
            $new_reg_no = isset($last_reg_no) ? (int) ($first_reg_no) : $first_reg_no;
        } else {
            $new_reg_no = isset($last_reg_no) ? (int) ($last_reg_no + 1) : $first_reg_no;
        }

        // DD($new_reg_no);
        $academic_year = AcademicYear::where('active', 1)->first();

        $student->update(['registration_no' => $new_reg_no, 'admission_year_id' => $academic_year->id]);
    }

    public static function studentListingQuery($request, $get = 0, $with = [], $whereIn = [])
    {
        $relations = [
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
            'student_previous_school',
            'class_students.academic_years',
            'class_students.branch_class_sections',
            'student_fee_package'
        ];

        if (count($with) > 0)
            $relations = array_merge($relations, $with);
        $data = Student::with($relations);

        if (count($whereIn) > 0)
            $data->whereIn('students.id', $whereIn);

        if (auth()->user()->hasRole('network_associate')) {
            $data = $data->where('branch_id', get_set_NWABranchId());
        }

        if ($request->academic_year_id && $request->academic_year_id != '') {
            $data = $data->whereHas('class_students', function ($query) use ($request) {
                $query->where('academic_year_id', $request->academic_year_id)
                    ->where('is_valid', 1);
            });
        }

        if ($request->region_id && $request->region_id > 0) {
            $data = $data->whereHas('branch', function ($query) use ($request) {
                $query->where('region_id', $request->region_id);
            });
        }
        if ($request->branch_id && $request->branch_id > 0) {
            // if(auth()->user()->hasRole('manager-business-development' || 'senior-manager-business-development')){
            //     $data = $data->whereHas('branch', function($query) use ($request){
            //         $query->where('region_id', $request->region_id);
            //     });
            // }
            // else{

            $data = $data->where('branch_id', $request->branch_id);
            // }

        } elseif (!isSuperAdmin() && !isHeadOfficeEmp() /*!auth()->user()->hasRole('manager-parent-relations')*/) {
            $data = $data->where('branch_id', get_branch_id());
        }

        if ($request->section_id && $request->section_id > 0) {
            $data = $data->whereHas('class_students', function ($query) use ($request) {
                $query->whereHas('branch_class_sections', function ($subQuery) use ($request) {
                    $subQuery->where('section_id', $request->section_id);
                    
                    // If class_id is provided, also filter by class
                    if ($request->class_id && $request->class_id > 0) {
                        $subQuery->where('class_id', $request->class_id);
                    }
                });
                
                // If academic year is provided, filter by it
                if ($request->academic_year_id && $request->academic_year_id != '') {
                    $query->where('academic_year_id', $request->academic_year_id);
                }
            });
        }

        if ($request->class_id && $request->class_id > 0) {
            $data = $data->whereHas('active_class.branch_class_sections', function ($query) use ($request) {
                $query->where('class_id', $request->class_id);
            });
        }

        if ($request->gender && $request->gender != '') {
            $data = $data->where('gender', $request->gender);
        }

        if ($request->status && $request->status != 'all') {
            if (in_array($request->status, ['on_roll', 'registered', 'left', 'pass-out', 'processing']))
                $data = $data->where('status', $request->status);
            elseif (in_array($request->status, ['transferred'])) {
                $data = $data->where('from_branch', '!=', null);
            } else
                $data = $data->whereNull('status');
        }

        if ($request->searchName && $request->searchName != null) {
            //dd($request->searchName);

            $data = $data->where(function ($query) use ($request) {
                $query->orWhere('first_name', 'like', '%' . $request->searchName . '%');
                $query->orWhere('middle_name', 'like', '%' . $request->searchName . '%');
                $query->orWhere('last_name', 'like', '%' . $request->searchName . '%');
                $query->orWhere('gender', 'like', '' . $request->searchName . '%');
                $query->orWhere('registration_no', 'like', '%' . $request->searchName . '%');
                $query->orWhere('roll_no', 'like', '%' . $request->searchName . '%');
            });
        }

        // Apply ordering at the end to ensure it works correctly with all joins
        $data = $data->orderBy('students.id', 'desc');

        return $get ? $data->get() : $data;
    }

    public function student_promotion_request()
    {
        return $this->hasOne(StudentPromotionRequest::class, 'student_id', 'id');
    }
    public function student_withdrawals()
    {
        return $this->hasOne(StudentWithdrawal::class, 'student_id', 'id');
    }

    public function ptm_info()
    {
        return $this->hasOne(PTM::class, 'student_id', 'id');
    }

    public function extra_curriculum_info()
    {
        return $this->hasOne(ExtraCurriculum::class, 'student_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(StudentPayment::class, 'student_id', 'id');
    }

    public function arrears_history()
    {
        return $this->hasMany(StudentArrearsHistory::class, 'student_id', 'id');
    }

    public function invoices()
    {
        return $this->hasMany(StudentInvoice::class, 'student_id', 'id');
    }
}
