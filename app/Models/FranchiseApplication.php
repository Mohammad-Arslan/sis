<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseApplication extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'appl_name',
        'appl_last_name',
        'CNIC',
        'personal_address',
        'email',
        'entry_ip',
        'status',
        'agreement_type',
        'entered_by',
        'state_id',
        'city_id',
        'source_id',
        'contact_no_1',
        'contact_no_2',
        'gender',
        'marital_status',
        'qualification',
        'current_occupation',
        'current_employer',
        'last_designation',
        'professional_background',
        'other_professional_background',
        'business_name',
        'proprietary',
        'nature_of_business',
        'offered_services',
        'business_years',
        'number_of_people_employed',
        'turn_over',
        'criminal_record',
        'criminal_proceedings',
        'unlawful_acts',
        'criminal_record_details',
        'proposed_property_status',
        'school_franchise_capacity',
        'propose_to_setup_school',
        'company_already_in_existence',
        'business_firm_company_name',
        'setup_propose_city',
        'duration_new_venture_setup',
        'already_possess_site',
        'site_in_mind',
        'plan_rent_site',
        'no_of_month_for_rent_a_site',
        'initial_funding',
        'business_success_initiative',
        'suitable_franchisee_reason',
        'recommended_by',
        'recommended_date',
        'application_status_remarks',
        'approved_by',
        'approved_date',
        'forwarded_by',
        'forwarded_date',
        'seat_no',
        'constituency_id',
        'loi_token_amount',
        'execution_date',
        'cut_off_date',
        'extension_date'
    ];

    protected $dates = [
        'recommended_date',
        'approved_date',
        'forwarded_date'
    ];

    public function cities()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function states()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id', 'id');
    }

    public function task()
    {
        return $this->morphOne(Task::class, 'taskable');
    }

    public function recommendedBy(){
        return $this->belongsTo(User::class,'recommended_by','id');
    }

    public function other_informations()
    {
        return $this->belongsTo(FranchiseOtherInformation::class, 'id', 'inquiry_id');
    }

    public function franchise_application_qa(){
        return $this->hasOne(FranchiseApplicationQa::class,'franchise_application_id','id')->latestOfMany();
    }

    public function franchise_application_bd(){
        return $this->hasOne(FranchiseApplicationBdVisit::class,'franchise_application_id','id')->latestOfMany();
    }

    public function franchise_application_bd_approved(){
        return $this->hasOne(FranchiseApplicationBdVisit::class,'franchise_application_id','id')->where('bd_status','approved')->latest('id');
    }

    public function franchise_application_tor(){
        return $this->hasOne(FranchiseApplicationTorsResponse::class,'franchise_application_id','id')->latestOfMany();
    }

    public function franchise_application_legal(){
        return $this->hasOne(FranchiseApplicationLaResponse::class,'franchise_application_id','id')->latestOfMany();
    }

    public function franchise_application_dd(){
        return $this->hasOne(FranchiseApplicationDdResponse::class,'franchise_application_id','id')->latestOfMany();
    }

    public function franchise_application_observation(){
        return $this->hasOne(FrachiseApplicationRemark::class,'franchise_application_id','id')->latestOfMany();
    }

    public function franchise_application_qa_remarks(){
        return $this->hasOne(FrachiseApplicationRemark::class,'franchise_application_id','id')->where('observation_for','IASF')->where('user_role','sr_officer_quality_assurance')->orWhere('user_role','manager-quality-assurance')->latest('id');
    }
    public function franchise_application_bd_remarks(){
        return $this->hasOne(FrachiseApplicationRemark::class,'franchise_application_id','id')->where('observation_for','IASF')->where('user_role','manager-business-development')->orWhere('user_role','senior-manager-business-development')->latest('id');
    }
    public function franchise_application_dd_remarks(){
        return $this->hasOne(FrachiseApplicationRemark::class,'franchise_application_id','id')->where('observation_for','IASF')->where('user_role','deputy-director')->latest('id');
    }
    //->whereIn('user_role',['deputy-director','sr_officer_quality_assurance','manager-quality-assurance','manager-business-development','senior-manager-business-development'])
}
