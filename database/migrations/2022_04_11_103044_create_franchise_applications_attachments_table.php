<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseApplicationsAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_applications_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attachment_type_id')->nullable();
            $table->foreign('attachment_type_id')->references('id')->on('franchise_application_attachment_types');
            $table->string('actual_name')->nullable();
            $table->string('type')->nullable();
            $table->string('size')->nullable();  
            $table->string('tmp_name')->nullable();
            $table->string('details')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->foreign('uploaded_by')->references('id')->on('users');
            $table->date('uploaded_date')->nullable();
            $table->unsignedBigInteger('inquiry_id')->nullable();
            $table->foreign('inquiry_id')->references('id')->on('franchise_inquiries');
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
        Schema::dropIfExists('franchise_applications_attachments');
    }
}
