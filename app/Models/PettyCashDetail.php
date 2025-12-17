<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'opening_balance',
        'amount_received',
        'expense',
        'closing_balance',
        'details_purpose',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'expense' => 'decimal:2',
        'closing_balance' => 'decimal:2',
    ];

    /**
     * Get the branch that owns the Petty Cash Detail.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
