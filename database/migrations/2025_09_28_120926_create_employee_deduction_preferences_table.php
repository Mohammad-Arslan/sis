<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDeductionPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_deduction_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('deduction_type_id')->constrained('deduction_types');
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('type', ['fixed', 'percentage'])->default('fixed');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['employee_id', 'is_active']);
            $table->index(['deduction_type_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_deduction_preferences');
    }
}
