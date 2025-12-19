<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class State extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    use LogsActivity;

    protected $fillable = [
        'state_name',
        'country_id',
    ];

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function contactInformation()
    {
        return $this->hasMany(ContactInformation::class, 'state_id', 'id');
    }
}
