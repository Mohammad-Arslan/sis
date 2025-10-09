<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $casts = [
        'items' => 'array',
        'total_cost' => 'float',
        'expected_delivery_date' => 'date',
        'delivery_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected $fillable = [
        'po_number',
        'purchase_request_id',
        'supplier_id',
        'branch_id',
        'department_id',
        'user_id',
        'created_by',
        'approved_by',
        'rejected_by',
        'received_by',
        'status',
        'total_cost',
        'items',
        'remarks',
        'payment_terms',
        'shipping_terms',
        'expected_delivery_date',
        'delivery_date',
        'delivery_address',
        'billing_address',
        'shipping_method',
        'discount_amount',
        'tax_amount',
        'shipping_cost',
    ];

    // Define relationships
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Generate a unique PO number
    public static function generatePONumber()
    {
        $prefix = 'PO-';
        $date = now()->format('Ymd');
        $lastPO = self::where('po_number', 'like', $prefix . $date . '%')->latest()->first();
        
        if ($lastPO) {
            $lastNumber = intval(substr($lastPO->po_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
