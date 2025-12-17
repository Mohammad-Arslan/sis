<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_applications', function (Blueprint $table) {
            $table->id();
            $table->string('appl_name', 50)->nullable();
            $table->string('appl_last_name', 50)->nullable();
            $table->string('CNIC', 15)->nullable();
            $table->string('personal_address', 200)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('entry_ip', 50)->nullable();
            $table->string('status', 1)->default('P');
            $table->string('agreement_type')->nullable();
            $table->integer('entered_by')->default('999950');


            $table->unsignedBigInteger('recommended_by')->nullable();
            $table->foreign('recommended_by')->references('id')->on('users');
            $table->date('recommended_date')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
            $table->date('approved_date')->nullable();

            $table->unsignedBigInteger('forwarded_by')->nullable();
            $table->foreign('forwarded_by')->references('id')->on('users');
            $table->date('forwarded_date')->nullable();

            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->unsignedBigInteger('source_id');
            $table->foreign('source_id')->references('id')->on('sources');

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

            $table->string('criminal_record')->default('N');
            $table->string('criminal_proceedings')->default('N');
            $table->string('unlawful_acts')->default('N');
            $table->string('criminal_record_details')->nullable();


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
            $table->integer('state_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_applications');
    }
}
