<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class BankAccount extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'bank_name',
        'branch_code',
        'branch_address',
        'account_title',
        'account_no',
        'IBAN',
        'is_default'
    ];

    public function bank_accountable()
    {
        return $this->morphTo();
    }
}
