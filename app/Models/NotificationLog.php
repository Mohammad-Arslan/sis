<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'guardian_id',
        'notification_type',
        'notification_body',
        'status',
        'system_notification_id',
    ];
    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'date:d-m-Y',
    ];

    public function notification()
    {
        return $this->belongsTo(SystemNotification::class, 'system_notification_id', 'id');
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }
}
