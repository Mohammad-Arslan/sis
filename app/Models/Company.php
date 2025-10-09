<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Company extends Model
{
    use HasFactory,SerializeDateTrait;

    protected $fillable = [
        'company_name',
        'description',
    ];

    protected $dates = [

        'created_at',
        'updated_at',
    ];

    public function networkAssociates()
    {
        return $this->hasMany(NetworkAssociate::class, 'company_id', 'id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function fee_charges()
    {
        return $this->hasMany(FeeCharge::class);
    }

    public function fee_packages()
    {
        return $this->hasMany(FeePackage::class);
    }

    public function fee_concessions()
    {
        return $this->hasMany(FeeConcession::class);
    }

    public function bank_accounts()
    {
        return $this->morphMany(BankAccount::class, 'bank_accountable');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class,'company_id','id');
    }
}
