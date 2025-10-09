<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseApplicationService extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'franchise_application_id',
        'from_date',
        'to_date',
        'organisation',
        'designation',
        'responsibilities'
    ];

    protected $dates = [
        'from_date',
        'to_date'
    ];
}
