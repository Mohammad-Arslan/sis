<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HmCampusRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'area',
        'time_from',
        'time_to',
        'purpose',
    ];

    protected $casts = [
        'time_from' => 'datetime:H:i',
        'time_to' => 'datetime:H:i',
    ];

    /**
     * Get the branch that owns the HM Campus Round.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
