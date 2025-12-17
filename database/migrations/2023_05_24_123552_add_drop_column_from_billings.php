<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDropColumnFromBillings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn('franchise_percentage');
            $table->dropColumn('state_name');
            $table->dropColumn('branch_code');
            $table->dropColumn('franchise_ac_number');
            $table->string('order_id')->after('id');
            $table->unsignedBigInteger('branch_id')->after('invoice_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->double('discountable_charges')->after('branch_id')->nullable();
            $table->double('non_refundable_charges')->after('discountable_charges')->nullable();
            $table->double('sibling_discount_percentage')->after('non_refundable_charges')->nullable();
            $table->double('concession_type')->after('sibling_discount_percentage')->nullable();
            $table->double('concession_percentage')->after('concession_type')->nullable();
            $table->double('concession_discount')->after('concession_percentage')->nullable();
            $table->double('royalty_amount')->after('royalty_percentage')->nullable();
            $table->double('total_after_royalty')->after('royalty_amount')->nullable();
            $table->double('arrears')->after('total_after_royalty')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropForeign(['billings_branch_id_foreign']);
            $table->dropColumn('discountable_charges');
            $table->dropColumn('non_refundable_charges');
            $table->dropColumn('sibling_discount_percentage');
            $table->dropColumn('concession_type');
            $table->dropColumn('concession_percentage');
            $table->dropColumn('concession_discount');
            $table->dropColumn('royalty_amount');
            $table->dropColumn('total_after_royalty');
            $table->dropColumn('arrears');
        });
    }
}
