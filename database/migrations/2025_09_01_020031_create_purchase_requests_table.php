<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('branch_id')->constrained('branches');
            $table->foreignId('department_id')->constrained('departments');
            // Fix for foreign key constraint: explicitly define column type and reference
            $table->foreignId('user_id')->constrained('users' );
            $table->string('status')->default('pending');
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->json('items'); // JSON field to store items
            $table->text('remarks')->nullable();
            $table->date('required_date')->nullable();
            $table->string('budget_code')->nullable();
            $table->text('justification')->nullable();
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
        Schema::dropIfExists('purchase_requests');
    }
}
