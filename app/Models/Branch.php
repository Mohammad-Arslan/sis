<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BranchCodeTrait;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Branch extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    use BranchCodeTrait;

    protected $fillable = [
        'br_name',
        'abbreviation',
        'branch_phone_number',
        'region_id',
        'state_id',
        'fee_period_id',
        'building_type_id',
        'build_purpose',
        'website',
        'instagram',
        'twitter',
        'branch_banner',
        'status',
        'setup_date',
        'closed_date',
        'closing_reason',
        'class_group_id',
        'student_id_from',
        'student_id_to',
        'company_id'
    ];
    protected $dates = ['date_of_birth', 'closed_date'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function nwa()
    {
        return $this->hasOneThrough(
            NetworkAssociate::class,
            NetworkAssociateBranch::class,
            'branch_id',
            'id',
            'id',
            'nwa_id'
        );
    }

    //    public function brName(): Attribute
    //    {
    //        return new Attribute(
    //            get: fn ($value, $attributes) => $attributes['id'] . ' - ' . $attributes['br_name']
    //        );
    //    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function system_notifications()
    {
        return $this->hasMany(SystemNotification::class);
    }

    public function fee_charges()
    {
        return $this->hasMany(FeeCharge::class);
    }

    public function fee_packages()
    {
        return $this->hasMany(FeePackage::class);
    }

    public function status(): Attribute
    {
        // return null;
        return new Attribute(
            get: fn ($value) => $value == 1 ? '<span class="badge badge-outline-success">Active</span>' : '<span class="badge badge-outline-danger">Inactive</span>'
        );
    }

    public function contact_information()
    {
        return $this->morphOne(ContactInformation::class, 'contact_informationable');
    }

    public function fee_concessions()
    {
        return $this->hasMany(FeeConcession::class);
    }

    public function branch_class_section()
    {
        return $this->hasMany(BranchClassSection::class);
    }

    public function branch_academic_years()
    {
        return $this->hasMany(BranchAcademicYear::class);
    }
    public function employee()
    {
        return $this->hasMany(Employee::class);
    }

    public function fee_period()
    {
        return $this->belongsTo(FeePeriod::class, 'fee_period_id', 'id');
    }

    public function build_type()
    {
        return $this->belongsTo(BuildingType::class, 'building_type_id', 'id');
    }

    public function bank_accounts()
    {
        return $this->morphMany(BankAccount::class, 'bank_accountable');
    }

    public function default_bank_account()
    {
        return $this->morphOne(BankAccount::class, 'bank_accountable')->where('is_default', 1);
    }

    public function branch_royalties()
    {
        return $this->hasMany(BranchRoyalty::class, 'branch_id', 'id');
    }

    public function active_royalty()
    {
        return $this->hasOne(BranchRoyalty::class, 'branch_id', 'id')->where('with_effect_from', '<=', date('Y-m-d'))->where('closing_date', '>=', date('Y-m-d'));
    }

    public function tax_types()
    {
        return $this->belongsToMany(TaxType::class, 'branch_taxes', 'branch_id', 'tax_type_id');
    }

    public function class_group()
    {
        return $this->belongsTo(ClassGroup::class, 'class_group_id', 'id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    /**
     * Get assets assigned to this branch
     */
    public function assets()
    {
        return $this->hasMany(Asset::class, 'current_branch_id');
    }
}
