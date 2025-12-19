<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionRequest extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'student_id',
        'prev_branch_id',
        'prev_class_id',
        'prev_section_id',
        'prev_branch_class_section_id',
        'prev_academic_year_id',
        'cur_branch_id',
        'cur_class_id',
        'cur_section_id',
        'cur_branch_class_section_id',
        'cur_academic_year_id',
        'created_by',
        'approved_by',
        'rejected_by',
        'type',
        'is_promotion',
        'status',
        'approval_remarks',
        'rejection_remarks',
        'approved_date',
        'rejected_date'
    ];

    protected $dates = [
        'approved_date',
        'rejected_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function prev_branch()
    {
        return $this->belongsTo(Branch::class, 'prev_branch_id', 'id');
    }

    public function prev_class()
    {
        return $this->belongsTo(ComClass::class, 'prev_class_id', 'id');
    }

    public function prev_section()
    {
        return $this->belongsTo(Section::class, 'prev_section_id', 'id');
    }

    public function prev_branch_class_section()
    {
        return $this->belongsTo(BranchClassSection::class, 'prev_branch_class_section_id', 'id');
    }

    public function prev_academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'prev_academic_year_id', 'id');
    }

    public function cur_branch()
    {
        return $this->belongsTo(Branch::class, 'cur_branch_id', 'id');
    }

    public function cur_class()
    {
        return $this->belongsTo(ComClass::class, 'cur_class_id', 'id');
    }

    public function cur_section()
    {
        return $this->belongsTo(Section::class, 'cur_section_id', 'id');
    }

    public function cur_branch_class_section()
    {
        return $this->belongsTo(BranchClassSection::class, 'cur_branch_class_section_id', 'id');
    }

    public function cur_academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'cur_academic_year_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function rejected_by()
    {
        return $this->belongsTo(User::class, 'rejected_by', 'id');
    }



    public function student_promotion_requests()
    {
        return $this->hasMany(StudentPromotionRequest::class, 'promotion_request_id', 'id');
    }

    public function student_promotion_request()
    {
        return $this->hasOne(StudentPromotionRequest::class, 'promotion_request_id', 'id');
    }
}
