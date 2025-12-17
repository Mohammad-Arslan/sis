<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $casts = [
        'items' => 'array',
        'total_cost' => 'float',
        'required_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected $fillable = [
        'supplier_id',
        'requested_by',
        'status',
        'total_cost',
        'items', // JSON field to store items
        'remarks',
        'justification',
        'required_date',
        'budget_code',
        'branch_id',
        'department_id',
        'user_id',
        'approved_by',
        'rejected_by',
        'approved_at',
        'rejected_at',
    ];

    // Define relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function purchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    // Add additional methods if needed for business logic
}
