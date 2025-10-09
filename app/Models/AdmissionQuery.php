<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionQuery extends Model
{
    use HasFactory,SerializeDateTrait,SoftDeletes;

    protected $fillable = [
        'inquiry_number',
        'student_name',
        'student_age',
        'parent_name',
        'parent_email',
        'parent_contact',
        'inquiry_type_id',
        'city_id',
        'town_id',
        'branch_id',
        'class_id',
        'source_id',
        'academic_year_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function city(){
        return $this->belongsTo(City::class,'city_id','id');
    }

    public function town(){
        return $this->belongsTo(Town::class,'town_id','id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function com_class(){
        return $this->belongsTo(ComClass::class,'class_id','id');
    }

    public function source(){
        return $this->belongsTo(Source::class,'source_id','id');
    }
    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class,'academic_year_id','id');
    }

    public function inquiry_type()
    {
        return $this->belongsTo(InquiriesType::class, 'inquiry_type_id','id');
    }

    public static function store_update_admission_query($request,$admissionQuery = null,$type = 'create'){

        if ($type == 'create') {
            $academicYear = AcademicYear::where('active',1)->get('id')->toArray();
            $input = $request;
            $input['inquiry_type_id'] = 1;
            $input['academic_year_id'] = $academicYear[0]['id'];
            $input['inquiry_number'] = random_int(10000000,99999999);
            $response = self::create($input);
        }
        else if ($type == 'update'){
            $admissionQuery->update($request);
            $response = $admissionQuery->refresh();
        }

        return $response;
    }
}
