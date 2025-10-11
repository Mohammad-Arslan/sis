<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTaxPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_tax_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('tax_slab_id')->nullable()->constrained('income_tax_slabs');
            $table->decimal('tax_exemption_amount', 10, 2)->default(0);
            $table->boolean('apply_tax')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique('employee_id');
            $table->index(['employee_id', 'apply_tax']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_tax_preferences');
    }
}
