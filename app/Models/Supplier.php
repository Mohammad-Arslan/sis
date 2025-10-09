<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_id',
        'bank_details',
        'compliance_certificates',
        'category_ids',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'category_ids' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Generate a new unique supplier code
     * 
     * @return string
     */
    public static function generateCode()
    {
        // Always use SUP as the prefix
        $prefix = 'SUP';
        
        // Generate a random 5-digit number
        $code = $prefix . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        
        // Check if code already exists
        while (self::where('code', $code)->exists()) {
            $code = $prefix . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
        
        return $code;
    }

    /**
     * Get categories related to this supplier
     * 
     * This is a custom method to access categories based on category_ids
     */
    public function categories()
    {
        if (!$this->category_ids) {
            return collect([]);
        }
        
        return AssetCategory::whereIn('id', $this->category_ids)->get();
    }

    /**
     * Scope to get only active suppliers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
