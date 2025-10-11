<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_tag')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->timestamp('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->timestamp('warranty_end_date')->nullable();
            $table->enum('condition', ['new', 'good', 'fair', 'poor', 'damaged'])->default('new');
            $table->enum('status', ['active', 'inactive', 'maintenance', 'retired', 'lost', 'stolen'])->default('active');
            $table->unsignedBigInteger('current_branch_id');
            $table->unsignedBigInteger('current_department_id')->nullable();
            $table->unsignedBigInteger('assigned_to_user_id')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('image_url')->nullable();
            $table->json('specifications')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('category_id')->references('id')->on('asset_categories')->onDelete('restrict');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->foreign('current_branch_id')->references('id')->on('branches')->onDelete('restrict');
            $table->foreign('current_department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('assigned_to_user_id')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index('asset_tag');
            $table->index('name');
            $table->index('category_id');
            $table->index('supplier_id');
            $table->index('status');
            $table->index('condition');
            $table->index('current_branch_id');
            $table->index('assigned_to_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
}
