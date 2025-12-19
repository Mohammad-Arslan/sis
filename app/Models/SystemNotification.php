<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemNotification extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'country_id',
        'state_id',
        'user_id',
        'branch_id',
        'class_id',
        'section_id',
        'audience',
        'notification_type',
        'message',
        'email_header',
        'email_body',
        'email_salutation',
    ];
    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'date:d-m-Y H:m',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function guardians()
    {
        return $this->hasMany(Guardian::class, 'id', 'user_id')->with('students');
    }
}
