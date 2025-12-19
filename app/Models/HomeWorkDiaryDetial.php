<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SerializeDateTrait;

class HomeWorkDiaryDetial extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'diary_id',
        'subject_id',
        'homework',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function diary()
    {
        return $this->belongsTo(HomeWorkDiary::class, 'diary_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    // public function attachment()
    // {
    //     return $this->morphMany(HomeWorkDiaryAttachment::class, 'attachmentable');
    // }
}
