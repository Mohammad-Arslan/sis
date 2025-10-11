<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportQuery extends Model
{
    use HasFactory;

    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'raised_by',
        'branch_id',
        'url',
        'file_name',
        'description',
        'priority',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class,'raised_by','id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}
