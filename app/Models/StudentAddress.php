<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class StudentAddress extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        "res_country_id",
        "res_state_id",
        "res_city_id",
        "res_town_id",
        "res_postal_code",
        "res_contact_person",
        "res_phone",
        "res_sms_number",
        "res_mobile",
        "per_city_id",
        "street_address",
        "per_phone",
        "per_postal_code",
        "per_address",
        "student_id"
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
