<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiblingInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'family_information_id',
        'student_id',
        'sibling_no',
    ];

    public function family() {
        return $this->belongsTo(FamilyInformation::class, 'family_information_id', 'id');
    }

    public function student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
