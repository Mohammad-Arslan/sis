<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseApplicationsAttachment extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'franchise_application_id',
        'attachment_type_id',
        'file_name',
        'type',
        'details',
        'uploaded_by',
        'uploaded_date',
        'inquiry_id'
    ];

    protected $dates = ['uploaded_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }

    public function attachment_type()
    {
        return $this->belongsTo(FranchiseApplicationAttachmentType::class, 'attachment_type_id', 'id');
    }

    public function franchise_application()
    {
        return $this->belongsTo(FranchiseApplication::class, 'franchise_application_id', 'id');
    }
}
