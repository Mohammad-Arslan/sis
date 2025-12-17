<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitInterviewFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exit_interview_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            
            // Basic Information
            $table->string('employee_id_code')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('designation_role')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->date('last_working_day');
            $table->string('reporting_manager')->nullable();
            
            // Reason for Leaving
            $table->enum('reason_for_leaving_type', [
                'Better career opportunity',
                'Relocation (personal/family reasons)',
                'Compensation & benefits',
                'Work environment / culture',
                'Job role mismatch',
                'Lack of career growth',
                'Health or retirement',
                'Contract end / termination',
                'Other'
            ])->nullable();
            $table->text('reason_for_leaving_other')->nullable();
            
            // Job Satisfaction & Experience (ratings: 1-5 scale)
            $table->integer('overall_job_satisfaction')->nullable(); // 1-5
            $table->integer('relationship_with_supervisor_rating')->nullable();
            $table->text('relationship_with_supervisor_comments')->nullable();
            $table->integer('relationship_with_colleagues_rating')->nullable();
            $table->text('relationship_with_colleagues_comments')->nullable();
            $table->integer('training_development_rating')->nullable();
            $table->text('training_development_comments')->nullable();
            $table->integer('workload_worklife_balance_rating')->nullable();
            $table->text('workload_worklife_balance_comments')->nullable();
            
            // Compensation & Benefits
            $table->integer('salary_benefits_satisfaction')->nullable();
            $table->text('salary_benefits_comments')->nullable();
            $table->integer('performance_appraisal_fairness')->nullable();
            $table->text('performance_appraisal_comments')->nullable();
            
            // Organizational Culture & Environment
            $table->integer('work_environment_rating')->nullable();
            $table->text('work_environment_comments')->nullable();
            $table->integer('policies_procedures_rating')->nullable();
            $table->text('policies_procedures_comments')->nullable();
            $table->integer('communication_transparency_rating')->nullable();
            $table->text('communication_transparency_comments')->nullable();
            $table->text('staff_retention_suggestions')->nullable();
            
            // Exit Process & Feedback
            $table->json('clearance_status')->nullable(); // library, accounts, IT equipment, HR
            $table->boolean('would_recommend_company')->nullable();
            $table->text('recommendation_comments')->nullable();
            $table->boolean('would_rejoin_future')->nullable();
            $table->text('what_liked_most')->nullable();
            $table->text('what_liked_least')->nullable();
            $table->text('suggestions_for_improvement')->nullable();
            
            // Final Section
            $table->string('employee_signature')->nullable();
            $table->timestamp('employee_signature_date')->nullable();
            $table->string('hr_signature')->nullable();
            $table->timestamp('hr_signature_date')->nullable();
            
            // Status and Review
            $table->enum('status', ['draft', 'submitted', 'reviewed'])->default('draft');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('hr_notes')->nullable();
            
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
        Schema::dropIfExists('exit_interview_feedbacks');
    }
}
