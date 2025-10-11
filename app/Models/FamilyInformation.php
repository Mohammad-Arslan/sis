<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'guardian_id',
        'family_no',
        'CNIC'
    ];

    public static function create_record($data) {
        if (isset($data->guardian_id)) return;

        self::create([
            'guardian_id' => $data->guardian_id,
            'CNIC' => $data->CNIC,
            'family_no' => rand(100000, 999999)
        ]);
    }

    public function parent() {
        return $this->belongsTo(Guardian::class, 'guardian_id', 'id');
    }

    public function children() {
        return $this->hasMany(SiblingInformation::class);
    }
}
