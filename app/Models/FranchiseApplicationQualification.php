<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseApplicationQualification extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'franchise_application_id',
        'qualification',
        'passing_year',
        'institute'
    ];
}
