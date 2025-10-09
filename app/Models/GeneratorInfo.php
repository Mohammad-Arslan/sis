<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratorInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'date_refueling',
        'quantity_liter',
        'verified_by',
        'generator_capacity',
        'starting_time',
        'end_time',
        'reading',
    ];

    protected $casts = [
        'date_refueling' => 'date',
        'quantity_liter' => 'decimal:2',
        'starting_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the branch that owns the Generator Info.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
