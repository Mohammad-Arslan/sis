<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class NetworkAssociate extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;

    protected $fillable = [
        'user_id',
        'NTN',
        'STRN',
        'company_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function contact_information()
    {
        return $this->morphOne(ContactInformation::class, 'contact_informationable');
    }

    public function bank_accounts()
    {
        return $this->morphMany(BankAccount::class, 'bank_accountable');
    }

    public function default_bank_account()
    {
        return $this->morphOne(BankAccount::class, 'bank_accountable')->where('is_default',1);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class,'network_associate_branches','nwa_id','branch_id')->wherePivot('deleted_at',null);
    }
}
