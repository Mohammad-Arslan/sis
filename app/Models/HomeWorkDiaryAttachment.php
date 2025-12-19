<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SerializeDateTrait;

class HomeWorkDiaryAttachment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'diary_detail_id',
        'attachment',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function DiaryDetial()
    {
        return $this->belongsTo(HomeWorkDiaryDetial::class, 'diary_detail_id', 'id');
    }
}
