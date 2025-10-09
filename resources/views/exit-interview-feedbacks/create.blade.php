@extends('layouts.master')

@section('title', 'Create Exit Interview Feedback')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0 d-flex align-items-center">
                            <i class="fas fa-clipboard-list me-2"></i>
                            <span>Create Exit Interview Feedback</span>
                        </h3>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('exit-interview-feedbacks.store') }}" method="POST" id="exitInterviewForm">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light border-bottom">
                                <h5 class="mb-0 text-dark d-flex align-items-center">
                                    <i class="fas fa-user me-2"></i>
                                    <span>Basic Information</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="employee_id_code" class="font-weight-bold">Employee ID Code</label>
                                            <input type="text" class="form-control form-control-lg bg-light" 
                                                   id="employee_id_code" name="employee_id_code" 
                                                   value="{{ $employeeData['employee_id_code'] }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="department_id" class="font-weight-bold">Department</label>
                                            <input type="text" class="form-control form-control-lg bg-light" 
                                                   value="{{ $employeeData['department_id'] ? $departments->where('id', $employeeData['department_id'])->first()->department_name ?? '' : '' }}" readonly>
                                            <input type="hidden" name="department_id" value="{{ $employeeData['department_id'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="designation_role" class="font-weight-bold">Designation/Role</label>
                                            <input type="text" class="form-control form-control-lg bg-light" 
                                                   id="designation_role" name="designation_role" 
                                                   value="{{ $employeeData['designation_role'] }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_of_joining" class="font-weight-bold">Date of Joining</label>
                                            <input type="text" class="form-control form-control-lg bg-light" 
                                                   id="date_of_joining" name="date_of_joining" 
                                                   value="{{ $employeeData['date_of_joining'] }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_working_day" class="font-weight-bold">Last Working Day <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-lg @error('last_working_day') is-invalid @enderror" 
                                                   id="last_working_day" name="last_working_day" 
                                                   value="{{ old('last_working_day') }}" 
                                                   min="{{ date('Y-m-d') }}" required>
                                            @error('last_working_day')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="reporting_manager" class="font-weight-bold">Reporting Manager</label>
                                            <input type="text" class="form-control form-control-lg bg-light" 
                                                   id="reporting_manager" name="reporting_manager" 
                                                   value="{{ $employeeData['reporting_manager'] }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reason for Leaving Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light border-bottom">
                                <h5 class="mb-0 text-dark d-flex align-items-center">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    <span>Reason for Leaving</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="reason_for_leaving_type" class="font-weight-bold">Reason for Leaving <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-lg @error('reason_for_leaving_type') is-invalid @enderror" 
                                            id="reason_for_leaving_type" name="reason_for_leaving_type" required>
                                        <option value="">Select Reason</option>
                                        <option value="Better career opportunity" {{ old('reason_for_leaving_type') == 'Better career opportunity' ? 'selected' : '' }}>Better career opportunity</option>
                                        <option value="Relocation (personal/family reasons)" {{ old('reason_for_leaving_type') == 'Relocation (personal/family reasons)' ? 'selected' : '' }}>Relocation (personal/family reasons)</option>
                                        <option value="Compensation & benefits" {{ old('reason_for_leaving_type') == 'Compensation & benefits' ? 'selected' : '' }}>Compensation & benefits</option>
                                        <option value="Work environment / culture" {{ old('reason_for_leaving_type') == 'Work environment / culture' ? 'selected' : '' }}>Work environment / culture</option>
                                        <option value="Job role mismatch" {{ old('reason_for_leaving_type') == 'Job role mismatch' ? 'selected' : '' }}>Job role mismatch</option>
                                        <option value="Lack of career growth" {{ old('reason_for_leaving_type') == 'Lack of career growth' ? 'selected' : '' }}>Lack of career growth</option>
                                        <option value="Health or retirement" {{ old('reason_for_leaving_type') == 'Health or retirement' ? 'selected' : '' }}>Health or retirement</option>
                                        <option value="Contract end / termination" {{ old('reason_for_leaving_type') == 'Contract end / termination' ? 'selected' : '' }}>Contract end / termination</option>
                                        <option value="Other" {{ old('reason_for_leaving_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('reason_for_leaving_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group" id="other_reason_div" style="display: none;">
                                    <label for="reason_for_leaving_other" class="font-weight-bold">Please specify other reason</label>
                                    <textarea class="form-control @error('reason_for_leaving_other') is-invalid @enderror" 
                                              id="reason_for_leaving_other" name="reason_for_leaving_other" 
                                              rows="3" placeholder="Please provide details...">{{ old('reason_for_leaving_other') }}</textarea>
                                    @error('reason_for_leaving_other')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Job Satisfaction & Experience Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light border-bottom">
                                <h5 class="mb-0 text-dark d-flex align-items-center">
                                    <i class="fas fa-star me-2"></i>
                                    <span>Job Satisfaction & Experience</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="font-weight-bold">Overall Job Satisfaction (1-5 scale) <span class="text-danger">*</span></label>
                                    <div class="rating-container">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="rating-option">
                                                <input type="radio" name="overall_job_satisfaction" value="{{ $i }}" 
                                                       {{ old('overall_job_satisfaction') == $i ? 'checked' : '' }} required>
                                                <span class="rating-number">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('overall_job_satisfaction')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Supervisor Relationship -->
                                <div class="form-group">
                                    <label class="font-weight-bold">Relationship with Supervisor/Management (1-5 scale) <span class="text-danger">*</span></label>
                                    <div class="rating-container">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="rating-option">
                                                <input type="radio" name="relationship_with_supervisor_rating" value="{{ $i }}" 
                                                       {{ old('relationship_with_supervisor_rating') == $i ? 'checked' : '' }} required>
                                                <span class="rating-number">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('relationship_with_supervisor_rating')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <textarea class="form-control mt-2 @error('relationship_with_supervisor_comments') is-invalid @enderror" 
                                              name="relationship_with_supervisor_comments" 
                                              placeholder="Comments about supervisor relationship..." required>{{ old('relationship_with_supervisor_comments') }}</textarea>
                                    @error('relationship_with_supervisor_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Colleagues Relationship -->
                                <div class="form-group">
                                    <label class="font-weight-bold">Relationship with Colleagues (1-5 scale) <span class="text-danger">*</span></label>
                                    <div class="rating-container">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="rating-option">
                                                <input type="radio" name="relationship_with_colleagues_rating" value="{{ $i }}" 
                                                       {{ old('relationship_with_colleagues_rating') == $i ? 'checked' : '' }} required>
                                                <span class="rating-number">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('relationship_with_colleagues_rating')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <textarea class="form-control mt-2 @error('relationship_with_colleagues_comments') is-invalid @enderror" 
                                              name="relationship_with_colleagues_comments" 
                                              placeholder="Comments about colleague relationship..." required>{{ old('relationship_with_colleagues_comments') }}</textarea>
                                    @error('relationship_with_colleagues_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Training & Development -->
                                <div class="form-group">
                                    <label class="font-weight-bold">Training & Development Opportunities (1-5 scale) <span class="text-danger">*</span></label>
                                    <div class="rating-container">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="rating-option">
                                                <input type="radio" name="training_development_rating" value="{{ $i }}" 
                                                       {{ old('training_development_rating') == $i ? 'checked' : '' }} required>
                                                <span class="rating-number">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('training_development_rating')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <textarea class="form-control mt-2 @error('training_development_comments') is-invalid @enderror" 
                                              name="training_development_comments" 
                                              placeholder="Comments about training and development..." required>{{ old('training_development_comments') }}</textarea>
                                    @error('training_development_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Workload & Work-Life Balance -->
                                <div class="form-group">
                                    <label class="font-weight-bold">Workload & Work-Life Balance (1-5 scale) <span class="text-danger">*</span></label>
                                    <div class="rating-container">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="rating-option">
                                                <input type="radio" name="workload_worklife_balance_rating" value="{{ $i }}" 
                                                       {{ old('workload_worklife_balance_rating') == $i ? 'checked' : '' }} required>
                                                <span class="rating-number">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('workload_worklife_balance_rating')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <textarea class="form-control mt-2 @error('workload_worklife_balance_comments') is-invalid @enderror" 
                                              name="workload_worklife_balance_comments" 
                                              placeholder="Comments about workload and work-life balance..." required>{{ old('workload_worklife_balance_comments') }}</textarea>
                                    @error('workload_worklife_balance_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Compensation & Benefits Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light border-bottom">
                                <h5 class="mb-0 text-dark d-flex align-items-center">
                                    <i class="fas fa-dollar-sign me-2"></i>
                                    <span>Compensation & Benefits</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="font-weight-bold">Salary & Benefits Satisfaction (1-5 scale) <span class="text-danger">*</span></label>
                                    <div class="rating-container">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="rating-option">
                                                <input type="radio" name="salary_benefits_satisfaction" value="{{ $i }}" 
                                                       {{ old('salary_benefits_satisfaction') == $i ? 'checked' : '' }} required>
                                                <span class="rating-number">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('salary_benefits_satisfaction')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <textarea class="form-control mt-2 @error('salary_benefits_comments') is-invalid @enderror" 
                                              name="salary_benefits_comments" 
                                              placeholder="Comments about salary and benefits..." required>{{ old('salary_benefits_comments') }}</textarea>
                                    @error('salary_benefits_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Performance Appraisal Fairness (1-5 scale) <span class="text-danger">*</span></label>
                                    <div class="rating-container">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="rating-option">
                                                <input type="radio" name="performance_appraisal_fairness" value="{{ $i }}" 
                                                       {{ old('performance_appraisal_fairness') == $i ? 'checked' : '' }} required>
                                                <span class="rating-number">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('performance_appraisal_fairness')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <textarea class="form-control mt-2 @error('performance_appraisal_comments') is-invalid @enderror" 
                                              name="performance_appraisal_comments" 
                                              placeholder="Comments about performance appraisal fairness..." required>{{ old('performance_appraisal_comments') }}</textarea>
                                    @error('performance_appraisal_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Organizational Culture & Environment Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light border-bottom">
                                <h5 class="mb-0 text-dark d-flex align-items-center">
                                    <i class="fas fa-building me-2"></i>
                                    <span>Organizational Culture & Environment</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="font-weight-bold">Work Environment (1-5 scale) <span class="text-danger">*</span></label>
                                    <div class="rating-container">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="rating-option">
                                                <input type="radio" name="work_environment_rating" value="{{ $i }}" 
                                                       {{ old('work_environment_rating') == $i ? 'checked' : '' }} required>
                                                <span class="rating-number">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('work_environment_rating')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <textarea class="form-control mt-2 @error('work_environment_comments') is-invalid @enderror" 
                                              name="work_environment_comments" 
                                              placeholder="Comments about work environment..." required>{{ old('work_environment_comments') }}</textarea>
                                    @error('work_environment_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Policies & Procedures (1-5 scale) <span class="text-danger">*</span></label>
                                    <div class="rating-container">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="rating-option">
                                                <input type="radio" name="policies_procedures_rating" value="{{ $i }}" 
                                                       {{ old('policies_procedures_rating') == $i ? 'checked' : '' }} required>
                                                <span class="rating-number">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('policies_procedures_rating')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <textarea class="form-control mt-2 @error('policies_procedures_comments') is-invalid @enderror" 
                                              name="policies_procedures_comments" 
                                              placeholder="Comments about policies and procedures..." required>{{ old('policies_procedures_comments') }}</textarea>
                                    @error('policies_procedures_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Communication & Transparency (1-5 scale) <span class="text-danger">*</span></label>
                                    <div class="rating-container">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="rating-option">
                                                <input type="radio" name="communication_transparency_rating" value="{{ $i }}" 
                                                       {{ old('communication_transparency_rating') == $i ? 'checked' : '' }} required>
                                                <span class="rating-number">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('communication_transparency_rating')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <textarea class="form-control mt-2 @error('communication_transparency_comments') is-invalid @enderror" 
                                              name="communication_transparency_comments" 
                                              placeholder="Comments about communication and transparency..." required>{{ old('communication_transparency_comments') }}</textarea>
                                    @error('communication_transparency_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="staff_retention_suggestions" class="font-weight-bold">Suggestions for Staff Retention <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('staff_retention_suggestions') is-invalid @enderror" 
                                              id="staff_retention_suggestions" name="staff_retention_suggestions" 
                                              rows="4" placeholder="What suggestions do you have for improving staff retention?" required>{{ old('staff_retention_suggestions') }}</textarea>
                                    @error('staff_retention_suggestions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Exit Process & Feedback Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light border-bottom">
                                <h5 class="mb-0 text-dark d-flex align-items-center">
                                    <i class="fas fa-clipboard-check me-2"></i>
                                    <span>Exit Process & Feedback</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="font-weight-bold">Clearance Status (Check all that apply)</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="clearance_status[]" value="library" 
                                                       {{ in_array('library', old('clearance_status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label">Library</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="clearance_status[]" value="accounts" 
                                                       {{ in_array('accounts', old('clearance_status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label">Accounts</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="clearance_status[]" value="it_equipment" 
                                                       {{ in_array('it_equipment', old('clearance_status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label">IT Equipment</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="clearance_status[]" value="hr" 
                                                       {{ in_array('hr', old('clearance_status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label">HR</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Would you recommend this company to others? <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="would_recommend_company" value="1" 
                                               {{ old('would_recommend_company') == '1' ? 'checked' : '' }} required>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="would_recommend_company" value="0" 
                                               {{ old('would_recommend_company') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label">No</label>
                                    </div>
                                    @error('would_recommend_company')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <textarea class="form-control mt-2 @error('recommendation_comments') is-invalid @enderror" 
                                              name="recommendation_comments" 
                                              placeholder="Please explain your recommendation..." required>{{ old('recommendation_comments') }}</textarea>
                                    @error('recommendation_comments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Would you consider rejoining in the future? <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="would_rejoin_future" value="1" 
                                               {{ old('would_rejoin_future') == '1' ? 'checked' : '' }} required>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="would_rejoin_future" value="0" 
                                               {{ old('would_rejoin_future') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label">No</label>
                                    </div>
                                    @error('would_rejoin_future')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="what_liked_most" class="font-weight-bold">What did you like most about working here? <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('what_liked_most') is-invalid @enderror" 
                                              id="what_liked_most" name="what_liked_most" 
                                              rows="3" placeholder="Share what you enjoyed most..." required>{{ old('what_liked_most') }}</textarea>
                                    @error('what_liked_most')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="what_liked_least" class="font-weight-bold">What did you like least about working here? <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('what_liked_least') is-invalid @enderror" 
                                              id="what_liked_least" name="what_liked_least" 
                                              rows="3" placeholder="Share what could be improved..." required>{{ old('what_liked_least') }}</textarea>
                                    @error('what_liked_least')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="suggestions_for_improvement" class="font-weight-bold">Suggestions for Improvement <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('suggestions_for_improvement') is-invalid @enderror" 
                                              id="suggestions_for_improvement" name="suggestions_for_improvement" 
                                              rows="4" placeholder="Any suggestions to improve the organization?" required>{{ old('suggestions_for_improvement') }}</textarea>
                                    @error('suggestions_for_improvement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Create
                            </button>
                            <a href="{{ route('exit-interview-feedbacks.index') }}" class="btn btn-outline-secondary px-4 ms-3">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Improved Design */
.card {
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    padding: 1rem 1.5rem;
}

/* Removed gradient background for consistency */

.form-control-lg {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control-lg:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.bg-light {
    background-color: #f8f9fa !important;
}

/* Rating System */
.rating-container {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.rating-option {
    display: flex;
    align-items: center;
    cursor: pointer;
    position: relative;
}

.rating-option input[type="radio"] {
    display: none;
}

.rating-number {
    display: inline-block;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border: 3px solid #dee2e6;
    border-radius: 50%;
    background-color: #ffffff;
    color: #6c757d;
    font-weight: bold;
    font-size: 18px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.rating-option input[type="radio"]:checked + .rating-number {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

.rating-option:hover .rating-number {
    background-color: #e9ecef;
    border-color: #adb5bd;
    transform: scale(1.05);
}


/* Form Labels */
.font-weight-bold {
    font-weight: 600;
    color: #495057;
}

/* Space between icon and header text */
.card-header .fa,
.card-header .fas,
.card-header .far,
.card-header .fal,
.card-header .fab {
    margin-right: 0.5rem !important;
    margin-left: 0 !important;
}

.d-flex.align-items-center .fa,
.d-flex.align-items-center .fas,
.d-flex.align-items-center .far,
.d-flex.align-items-center .fal,
.d-flex.align-items-center .fab {
    margin-right: 0.5rem !important;
    margin-left: 0 !important;
}

/* Button Consistency */
.btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .rating-container {
        justify-content: center;
    }
    
    .rating-number {
        width: 40px;
        height: 40px;
        line-height: 40px;
        font-size: 16px;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}
</style>

<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const reasonSelect = document.getElementById('reason_for_leaving_type');
    const otherReasonDiv = document.getElementById('other_reason_div');
    
    function toggleOtherReason() {
        if (reasonSelect.value === 'Other') {
            otherReasonDiv.style.display = 'block';
            document.getElementById('reason_for_leaving_other').required = true;
        } else {
            otherReasonDiv.style.display = 'none';
            document.getElementById('reason_for_leaving_other').required = false;
        }
    }
    
    reasonSelect.addEventListener('change', toggleOtherReason);
    toggleOtherReason(); // Check on page load
});
</script>
@endsection