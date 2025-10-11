<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_number',
        'title',
        'description',
        'source_branch_id',
        'destination_branch_id',
        'source_department_id',
        'destination_department_id',
        'requested_by',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'transfer_date',
        'received_by',
        'received_at',
        'notes',
        'assigned_to_user_id'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'transfer_date' => 'datetime',
        'received_at' => 'datetime'
    ];

    public function transferItems()
    {
        return $this->hasMany(TransferItem::class);
    }

    public function sourceBranch()
    {
        return $this->belongsTo(Branch::class, 'source_branch_id');
    }

    public function destinationBranch()
    {
        return $this->belongsTo(Branch::class, 'destination_branch_id');
    }

    public function sourceDepartment()
    {
        return $this->belongsTo(Department::class, 'source_department_id');
    }

    public function destinationDepartment()
    {
        return $this->belongsTo(Department::class, 'destination_department_id');
    }

    public function requestedByUser()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function assignedToUser()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }
}
