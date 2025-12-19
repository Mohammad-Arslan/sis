<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassFeePackage extends Model
{
    use HasFactory;

    public $fillable = [
        'class_id',
        'fee_package_id'
    ];
}
