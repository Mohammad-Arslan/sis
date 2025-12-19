<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseApplicationTorsResponse extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'franchise_application_id',
        'class_group_id',
        'total_franchise_fee',
        'royalty_rate',
        'payment_on_mou',
        'mou_payment_mode',
        'payment_on_agreement',
        'agreement_payment_mode',
        'token_money',
        'token_money_mode',
        'renovation_period',
        'building_type',
        'new_renovate_date_from',
        'new_renovate_date_to',
        'agreement_type',
        'amount_received',
        'agreement_date',
        'operational_date',
        'actual_operational_date',
        'renewal_date',
        'bank_name',
        'bank_account',
        'bank_acc_opening_date',
        'review_by',
        'review_date',
        'forward_to',
        'status',
        'approval_date',
        'remarks',
    ];

    protected $dates = [
        'renovation_period',
        'new_renovate_date_from',
        'new_renovate_date_to',
        'agreement_date',
        'operational_date',
        'renewal_date',
        'bank_acc_opening_date',
        'review_date'
    ];

    public function reviewBy()
    {
        return $this->belongsTo(User::class, 'review_by', 'id');
    }

    public function forwardedTo()
    {
        return $this->belongsTo(User::class, 'forward_to', 'id');
    }

    public function franchise_application()
    {
        return $this->belongsTo(FranchiseApplication::class, 'franchise_application_id', 'id');
    }

    public function class_group()
    {
        return $this->belongsTo(ClassGroup::class, 'class_group_id', 'id');
    }
}
