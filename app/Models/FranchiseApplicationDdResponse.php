<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseApplicationDdResponse extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'franchise_application_id',
        'review_date',
        'review_by',
        'status',
        'remarks'
    ];

    protected $dates = [
        'review_date'
    ];

    public function user(){
        return $this->belongsTo(User::class,'review_by','id');
    }

    public function franchise_application(){
        return $this->belongsTo(FranchiseApplication::class,'franchise_application_id','id');
    }
}
