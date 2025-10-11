<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricityMeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'opening_day_reading',
        'closing_day_reading',
        'remark',
    ];

    /**
     * Get the branch that owns the Electricity Meter Reading.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
