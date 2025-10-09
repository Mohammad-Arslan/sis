<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallsDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'caller_name',
        'phone_number',
        'start_time',
        'end_time',
        'call_purpose',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the branch that owns the Calls Detail.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
