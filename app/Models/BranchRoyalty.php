<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchRoyalty extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'branch_id',
        'royalty_rate',
        //'sales_tax',
        //'fed',
        'with_effect_from',
        'closing_date',
        'updated_by',
        'remarks',
    ];

    protected $dates = [
        'with_effect_from',
        'closing_date'
    ];

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'updated_by','id');
    }
}
