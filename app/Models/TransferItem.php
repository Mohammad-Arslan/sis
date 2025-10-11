<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_request_id',
        'asset_id',
        'quantity',
        'condition',
        'notes'
    ];

    public function transferRequest()
    {
        return $this->belongsTo(TransferRequest::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
