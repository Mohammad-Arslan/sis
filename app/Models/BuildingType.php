<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuildingType extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    use LogsActivity;

    protected $fillable = [
       'type_name',
       'type_description'
    ];

    protected $dates = [
        'created_at' ,
        'updated_at',
    ];
}
