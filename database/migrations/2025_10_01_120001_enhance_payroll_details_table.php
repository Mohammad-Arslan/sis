<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhancePayrollDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            // For Allowances
            $table->boolean('is_permanent')->after('amount')->default(false)->comment('True if from salary structure, False if temporary/one-time');
            $table->boolean('is_taxable')->after('is_permanent')->default(true)->comment('True if included in tax calculation');
            
            // Category/Grouping
            $table->string('category')->nullable()->after('is_taxable')->comment('e.g., salary_component, statutory, loan, other');
            
            // Additional metadata
            $table->text('description')->nullable()->after('category')->comment('Additional details about the item');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->dropColumn([
                'is_permanent',
                'is_taxable',
                'category',
                'description',
            ]);
        });
    }
}


