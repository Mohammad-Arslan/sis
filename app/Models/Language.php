<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Language extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use LogsActivity;

    protected $fillable = [
        'language_name'
     ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function subjects()
    {
        return $this->hasMany(Language::class);
    }
}
