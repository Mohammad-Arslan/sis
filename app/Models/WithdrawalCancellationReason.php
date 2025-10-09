<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class WithdrawalCancellationReason extends Model
{
    use HasFactory,SerializeDateTrait;
    protected $fillable = [
        'cancellation_reason',
        'description'
     ];

     protected $dates = [
         'created_at',
         'updated_at',
     ];
}
