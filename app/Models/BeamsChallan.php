<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BeamsChallan extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'branch_id',
        'academic_year_id',
        'class_id',
        'section_id',
        'challan_month',
        'challan_pdf'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function branch_class_id()
    {
        return $this->belongsTo(ComClass::class, 'class_id', 'id');
    }

    public function class_section_id()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
}
