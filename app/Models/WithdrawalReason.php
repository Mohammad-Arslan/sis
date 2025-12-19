<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class WithdrawalReason extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'withdrawal_reason',
        'description'
     ];

     protected $dates = [

         'created_at',
         'updated_at',
     ];
}
