<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LedFranchise extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'company_name',
        'experience',
        'connected',
        'remarks',
        'inquirer_id'
    ];

    public function inquirer()
    {
        return $this->belongsTo(FranchiseInquiryOld::class, 'inquirer_id', 'id');
    }
}
