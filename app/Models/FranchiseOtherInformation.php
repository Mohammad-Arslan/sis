<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseOtherInformation extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
       'inquiry_id',
       'company_name',
       'designation',
       'experience',
       'personally_associated_with_org',
       'personally_associated_info',
       'family_member_associated_with_org',
       'family_associated_info'
    ];
}
