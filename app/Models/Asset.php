<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_tag',
        'name',
        'description',
        'category_id',
        'supplier_id',
        'serial_number',
        'model',
        'brand',
        'purchase_date',
        'purchase_price',
        'warranty_end_date',
        'condition',
        'status',
        'current_branch_id',
        'current_department_id',
        'assigned_to_user_id',
        'qr_code',
        'image_url',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'warranty_end_date' => 'datetime',
        'purchase_price' => 'decimal:2',
        'image_url' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Generate a new unique asset tag
     * 
     * @return string
     */
    public static function generateAssetTag()
    {
        // Always use AST as the prefix
        $prefix = 'AST';

        // Generate a random 6-digit number
        $tag = $prefix . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

        // Check if tag already exists
        while (self::where('asset_tag', $tag)->exists()) {
            $tag = $prefix . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        }

        return $tag;
    }

    /**
     * Generate QR code for the asset
     * 
     * @return string
     */
    public function generateQrCode()
    {
        return 'ASSET-' . $this->asset_tag;
    }

    /**
     * Relationship with AssetCategory
     */
    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    /**
     * Relationship with Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Relationship with Branch
     */
    public function currentBranch()
    {
        return $this->belongsTo(Branch::class, 'current_branch_id');
    }

    /**
     * Relationship with Department
     */
    public function currentDepartment()
    {
        return $this->belongsTo(Department::class, 'current_department_id');
    }

    /**
     * Relationship with User (assigned to)
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Scope to get only active assets
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get assets by condition
     */
    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }

    /**
     * Scope to get assets by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope to get assets by branch
     */
    public function scopeByBranch($query, $branchId)
    {
        return $query->where('current_branch_id', $branchId);
    }

    /**
     * Get formatted purchase price
     */
    public function getFormattedPurchasePriceAttribute()
    {
        return $this->purchase_price ? number_format($this->purchase_price, 2) : 'N/A';
    }

    /**
     * Get formatted purchase date
     */
    public function getFormattedPurchaseDateAttribute()
    {
        return $this->purchase_date ? $this->purchase_date->format('M d, Y') : 'N/A';
    }

    /**
     * Get formatted warranty end date
     */
    public function getFormattedWarrantyEndDateAttribute()
    {
        return $this->warranty_end_date ? $this->warranty_end_date->format('M d, Y') : 'N/A';
    }

    /**
     * Check if asset is under warranty
     */
    public function isUnderWarranty()
    {
        return $this->warranty_end_date && $this->warranty_end_date->isFuture();
    }

    /**
     * Get asset status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            'maintenance' => 'bg-warning',
            'retired' => 'bg-danger',
            'lost' => 'bg-danger',
            'stolen' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    /**
     * Get asset condition badge class
     */
    public function getConditionBadgeClassAttribute()
    {
        return match ($this->condition) {
            'new' => 'bg-success',
            'good' => 'bg-primary',
            'fair' => 'bg-warning',
            'poor' => 'bg-danger',
            'damaged' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function setImageUrlAttribute($value)
    {
        $this->attributes['image_url'] = json_encode($value, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Relationship with TransferItem
     */
    public function transferItems()
    {
        return $this->hasMany(TransferItem::class);
    }
}
