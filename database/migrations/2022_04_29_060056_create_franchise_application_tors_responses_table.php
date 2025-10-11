<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseApplicationTorsResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_application_tors_responses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('franchise_application_id')->nullable();
            $table->foreign('franchise_application_id','fa_fators_id_foreign')->references('id')->on('franchise_applications');

            $table->unsignedBigInteger('class_group_id');
            $table->foreign('class_group_id')->references('id')->on('class_groups');

            $table->float('total_franchise_fee')->nullable();
            $table->float('royalty_rate')->nullable();
            $table->float('payment_on_mou')->nullable();
            $table->string('mou_payment_mode')->nullable();
            $table->float('payment_on_agreement')->nullable();
            $table->string('agreement_payment_mode')->nullable();
            $table->float('token_money')->nullable();
            $table->string('token_money_mode')->nullable();
            $table->date('renovation_period')->nullable();
            $table->string('building_type')->nullable();
            $table->date('new_renovate_date_from')->nullable();
            $table->date('new_renovate_date_to')->nullable();
            $table->string('agreement_type')->nullable();
            $table->float('amount_received')->nullable();
            $table->date('agreement_date')->nullable();
            $table->date('operational_date')->nullable();
            $table->date('renewal_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->date('bank_acc_opening_date')->nullable();

            $table->unsignedBigInteger('review_by')->nullable();
            $table->foreign('review_by')->references('id')->on('users');
            $table->date('review_date')->nullable();

            $table->unsignedBigInteger('forward_to')->nullable();
            $table->foreign('forward_to')->references('id')->on('users');

            $table->string('status')->nullable();
            $table->mediumText('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_application_tors_responses');
    }
}
