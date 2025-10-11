<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class BranchTax extends Model
{
    use HasFactory,SerializeDateTrait;

    protected $fillable = [
        'branch_id',
        'tax_type_id'
    ];
}
