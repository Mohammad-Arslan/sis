<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrachiseApplicationRemark extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;

    protected $guarded = [];

    public function franchise_application()
    {
        return $this->belongsTo(FranchiseApplication::class, 'franchise_application_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
