<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class BranchClass extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'branch_id',
        'class_id'
    ];


    public function com_classes()
    {
        return $this->belongsTo(ComClass::class, 'class_id', 'id');
    }
}
