<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewFranchiseTableAndDropOldFranchiseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('franchise_inquiries', function (Blueprint $table) {

           

            $table->dropColumn([
                'primary_mobile_no',
                'secondary_mobile_no',
                'other_profession',
                'organization_name',
                'edu_organization_name',
                'inquirer_edu_designation',
                'inquirer_experience', 
                'edu_organization_desc',
                'inquirer_designation', 
                'inquirer_qualification', 
                'already_franchise',
                'remarks',
                'franchise_type',
                'campus_location',
                'time_required',
                'proposed_investment',
                'public_schools',
                'private_schools',
                'property_status',
                'financing_plan',
                'building_status',
                'building_ownership',
                'building_area',
                'building_address',
                'building_existing_area_size',
                'building_covered_area'
            ]);

            $table->dropForeign('franchise_inquiries_building_state_id_foreign');
            $table->dropColumn('building_state_id');
            $table->dropForeign('franchise_inquiries_building_city_id_foreign');
            $table->dropColumn('building_city_id');
            $table->dropForeign('franchise_inquiries_building_town_id_foreign');
            $table->dropColumn('building_town_id');

             

            $table->string('contact_no_1')->nullable();
            $table->string('contact_no_2')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->default('N');
            $table->string('qualification')->nullable();
            $table->string('current_occupation')->nullable();
            $table->string('current_employer')->nullable();
            $table->string('last_designation')->nullable();
            $table->string('professional_background')->nullable();
            $table->string('other_professional_background')->nullable();
            $table->string('business_name')->nullable();
            $table->string('proprietary')->nullable();
            $table->string('nature_of_business')->nullable();
            $table->string('offered_services')->nullable();
            $table->string('business_years')->nullable();
            $table->integer('number_of_people_employed')->nullable();
            $table->string('turn_over')->nullable();
            $table->string('proposed_property_status')->nullable();
            $table->string('school_franchise_capacity')->nullable();
            $table->string('propose_to_setup_school')->nullable();
            $table->string('company_already_in_existence')->nullable();
            $table->string('business_firm_company_name')->nullable();
            $table->string('setup_propose_city')->nullable();
            $table->string('duration_new_venture_setup')->nullable();
            $table->string('already_possess_site')->nullable();
            $table->string('site_in_mind')->nullable();
            $table->string('plan_rent_site')->nullable();
            $table->string('no_of_month_for_rent_a_site')->nullable();
            $table->string('initial_funding')->nullable();
            $table->string('business_success_initiative')->nullable();
            $table->string('suitable_franchisee_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->string('primary_mobile_no')->nullable();
            $table->string('secondary_mobile_no')->nullable();
            $table->string('other_profession')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('edu_organization_name')->nullable();
            $table->string('inquirer_edu_designation')->nullable();
            $table->string('inquirer_experience')->nullable();
            $table->string('edu_organization_desc')->nullable();
            $table->string('inquirer_designation')->nullable();
            $table->string('inquirer_qualification')->nullable();
            $table->string('already_franchise')->default('N');
            $table->string('remarks')->nullable();
            $table->string('franchise_type')->nullable();
            $table->string('campus_location')->nullable();

            $table->string('time_required')->nullable();
            $table->string('proposed_investment')->nullable();

            $table->integer('public_schools')->nullable();
            $table->integer('private_schools')->nullable();
            $table->string('property_status')->nullable();
            $table->string('financing_plan')->nullable();
            $table->string('building_status')->nullable();
            $table->string('building_ownership')->nullable();

            $table->string('building_area')->nullable();
            $table->string('building_address')->nullable();
            $table->string('building_existing_area_size')->nullable();
            $table->string('building_covered_area')->nullable();

            $table->unsignedBigInteger('building_state_id')->nullable();
            $table->foreign('building_state_id')->references('id')->on('states');
            $table->unsignedBigInteger('building_city_id')->nullable();
            $table->foreign('building_city_id')->references('id')->on('cities');
            $table->unsignedBigInteger('building_town_id')->nullable();
            $table->foreign('building_town_id')->references('id')->on('towns');

            $table->dropColumn([
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
                'suitable_franchisee_reason'
            ]);
           
        });
    }
}
