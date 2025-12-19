<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class StudentTransferReason extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'transfer_reason',
        'description'
     ];

     protected $dates = [
         'created_at',
         'updated_at',
     ];
}
