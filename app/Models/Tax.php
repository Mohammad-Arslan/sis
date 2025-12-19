<?php

namespace App\Models;

use App\Traits\CreatingObserverTrait;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Tax extends Model
{
    use HasFactory;
    use CreatingObserverTrait;
    use SerializeDateTrait;
    use LogsActivity;

    protected $fillable = [
        'tax_type_id',
        'created_by',
        'state_id',
        'tax_percentage',
        'active_from',
        'active_till',
    ];

    protected $casts = [
        'active_from' => 'date',
        'active_till' => 'date',
    ];

    public function tax_type()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }
}
