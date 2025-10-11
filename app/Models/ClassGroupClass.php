<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassGroupClass extends Model
{
    use HasFactory,SoftDeletes,SerializeDateTrait;

    protected $fillable=[
        'class_group_id',
        'class_id',
        'status'
    ];

    protected $dates = [

        'created_at',
        'updated_at',
    ];

    public function com_class(){
        return $this->belongsTo(ComClass::class,'class_id','id');
    }

    public function class_group(){
        return $this->belongsTo(ClassGroup::class,'class_group_id','id');
    }

}
