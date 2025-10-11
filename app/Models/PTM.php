<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PTM extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'ptm_date', 'ptm_type', 'discussion_summary', 'outcomes', 'notes'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
