<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionFollowUp extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;

    protected $fillable = [
        'admission_query_id',
        'followup_type_id',
        'next_follow_up_date',
        'remarks',
        'user_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function admission_query()
    {
        return $this->belongsTo(AdmissionQuery::class, 'admission_query_id', 'id');
    }

    public function followup_type()
    {
        return $this->belongsTo(FollowUpType::class, 'followup_type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
