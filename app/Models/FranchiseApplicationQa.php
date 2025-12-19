<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseApplicationQa extends Model
{
    protected $table = 'franchise_application_qa';
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = [
        'regional_head_sec_date',
        'visit_date'
    ];

    // public function qa_representative(){
    //     return $this->belongsTo(Employee::class,'qa_rep_id','id');
    // }

    public function sales_rep()
    {
        return $this->belongsTo(Employee::class, 'sales_rep_id', 'id');
    }

    public function qa_rep()
    {
        return $this->belongsTo(Employee::class, 'qa_rep_id', 'id');
    }

    public function regional_head()
    {
        return $this->belongsTo(Employee::class, 'regional_head_id', 'id');
    }

    public function class_group()
    {
        return $this->belongsTo(ClassGroup::class, 'school_type', 'id');
    }

    public function franchise_application()
    {
        return $this->belongsTo(FranchiseApplication::class, 'franchise_application_id', 'id');
    }

    public static function bd_forwarded_date($franchise_application_id)
    {
        $forwarded_date = null;
        $forwarded_date =  FranchiseApplicationBdVisit::where('franchise_application_id', $franchise_application_id)->latest('id')->first('forwarded_date');
        if (isset($forwarded_date)) {
            return Carbon::parse($forwarded_date->forwarded_date)->format('d-m-Y');
        }

        return '';
    }
}
