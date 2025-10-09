<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseApplicationLaResponse extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'franchise_application_id',
        'review_date',
        'review_by',
        'forwarded_to',
        'forwarded_date',
        'status',
        'remarks'
    ];

    public function user(){
        return $this->belongsTo(User::class,'review_by','id');
    }
    public function forwarded_user(){
        return $this->belongsTo(User::class,'forwarded_to','id');
    }

    public function franchise_application(){
        return $this->belongsTo(FranchiseApplication::class,'franchise_application_id','id');
    }
}
