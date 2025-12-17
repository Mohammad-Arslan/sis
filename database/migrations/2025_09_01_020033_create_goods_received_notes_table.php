<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsReceivedNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_received_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders');
            $table->string('grn_number')->unique();
            $table->date('received_date');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('branch_id')->constrained('branches');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('received_by')->constrained('users');
            $table->json('items');
            $table->decimal('total_cost', 15, 2);
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->text('remarks')->nullable();
            $table->string('delivery_note_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
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
        Schema::dropIfExists('goods_received_notes');
    }
}
