<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'nature_of_job',
        'locations',
        'name_of_reported_dep',
        'remarks',
    ];

    /**
     * Get the branch that owns the Repair Maintenance.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
