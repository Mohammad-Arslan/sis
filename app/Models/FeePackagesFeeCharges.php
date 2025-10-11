<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeePackagesFeeCharges extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'fee_package_id',
        'fee_charge_id',
        'status'
    ];

    public function fee_charges()
    {
        return $this->belongsTo(FeeCharge::class, 'fee_charge_id', 'id');
    }

    public function fee_package()
    {
        return $this->belongsTo(FeePackage::class, 'fee_package_id', 'id');
    }
}
