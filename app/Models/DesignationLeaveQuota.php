<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class DesignationLeaveQuota extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $guarded = [];

    public function leaveType()
    {
        return $this->hasOne(LeaveType::class, 'id', 'leave_type_id');
    }

    public function designation()
    {
        return $this->hasOne(Designation::class, 'id', 'designation_id');
    }
}
