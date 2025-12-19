<?php

namespace App\Http\Controllers;

use App\Models\ExitInterviewFeedback;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExitInterviewFeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if (! $user || ! $user->employee) {
            abort(403, 'Employee record not found.');
        }

        $feedbacks = ExitInterviewFeedback::with(['employee.user', 'employee.designation', 'reviewedBy', 'department'])
            ->where('employee_id', $user->employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('exit-interview-feedbacks.index', compact('feedbacks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (! $user || ! $user->employee) {
            abort(403, 'Employee record not found.');
        }

        $employee = $user->employee;
        $departments = Department::all();

        // Auto-populate employee data
        $employeeData = [
            'employee_id_code' => $employee->employee_id ?? '',
            'department_id' => $employee->department_id ?? '',
            'designation_role' => $employee->designation->designation_name ?? '',
            'date_of_joining' => $employee->hiring_date ? $employee->hiring_date->format('Y-m-d') : '',
            'reporting_manager' => $employee->reporting_manager ? $employee->reporting_manager->name : '',
        ];

        return view('exit-interview-feedbacks.create', compact('departments', 'employeeData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (! $user || ! $user->employee) {
            abort(403, 'Employee record not found.');
        }

        $request->validate([
            'last_working_day' => 'required|date|after_or_equal:today',
            'reason_for_leaving_type' => 'required|string',
            'reason_for_leaving_other' => 'nullable|string|max:1000',
            'overall_job_satisfaction' => 'required|integer|min:1|max:5',
            'relationship_with_supervisor_rating' => 'required|integer|min:1|max:5',
            'relationship_with_supervisor_comments' => 'required|string|max:1000',
            'relationship_with_colleagues_rating' => 'required|integer|min:1|max:5',
            'relationship_with_colleagues_comments' => 'required|string|max:1000',
            'training_development_rating' => 'required|integer|min:1|max:5',
            'training_development_comments' => 'required|string|max:1000',
            'workload_worklife_balance_rating' => 'required|integer|min:1|max:5',
            'workload_worklife_balance_comments' => 'required|string|max:1000',
            'salary_benefits_satisfaction' => 'required|integer|min:1|max:5',
            'salary_benefits_comments' => 'required|string|max:1000',
            'performance_appraisal_fairness' => 'required|integer|min:1|max:5',
            'performance_appraisal_comments' => 'required|string|max:1000',
            'work_environment_rating' => 'required|integer|min:1|max:5',
            'work_environment_comments' => 'required|string|max:1000',
            'policies_procedures_rating' => 'required|integer|min:1|max:5',
            'policies_procedures_comments' => 'required|string|max:1000',
            'communication_transparency_rating' => 'required|integer|min:1|max:5',
            'communication_transparency_comments' => 'required|string|max:1000',
            'staff_retention_suggestions' => 'required|string|max:1000',
            'would_recommend_company' => 'required|boolean',
            'recommendation_comments' => 'required|string|max:1000',
            'would_rejoin_future' => 'required|boolean',
            'what_liked_most' => 'required|string|max:1000',
            'what_liked_least' => 'required|string|max:1000',
            'suggestions_for_improvement' => 'required|string|max:1000',
        ]);

        $employee = $user->employee;

        $feedback = ExitInterviewFeedback::create([
            'employee_id' => $employee->id,
            'employee_id_code' => $employee->employee_id ?? '',
            'department_id' => $employee->department_id ?? null,
            'designation_role' => $employee->designation->designation_name ?? '',
            'date_of_joining' => $employee->hiring_date ?? null,
            'last_working_day' => $request->last_working_day,
            'reporting_manager' => $employee->reporting_manager ? $employee->reporting_manager->name : '',
            'reason_for_leaving_type' => $request->reason_for_leaving_type,
            'reason_for_leaving_other' => $request->reason_for_leaving_other,
            'overall_job_satisfaction' => $request->overall_job_satisfaction,
            'relationship_with_supervisor_rating' => $request->relationship_with_supervisor_rating,
            'relationship_with_supervisor_comments' => $request->relationship_with_supervisor_comments,
            'relationship_with_colleagues_rating' => $request->relationship_with_colleagues_rating,
            'relationship_with_colleagues_comments' => $request->relationship_with_colleagues_comments,
            'training_development_rating' => $request->training_development_rating,
            'training_development_comments' => $request->training_development_comments,
            'workload_worklife_balance_rating' => $request->workload_worklife_balance_rating,
            'workload_worklife_balance_comments' => $request->workload_worklife_balance_comments,
            'salary_benefits_satisfaction' => $request->salary_benefits_satisfaction,
            'salary_benefits_comments' => $request->salary_benefits_comments,
            'performance_appraisal_fairness' => $request->performance_appraisal_fairness,
            'performance_appraisal_comments' => $request->performance_appraisal_comments,
            'work_environment_rating' => $request->work_environment_rating,
            'work_environment_comments' => $request->work_environment_comments,
            'policies_procedures_rating' => $request->policies_procedures_rating,
            'policies_procedures_comments' => $request->policies_procedures_comments,
            'communication_transparency_rating' => $request->communication_transparency_rating,
            'communication_transparency_comments' => $request->communication_transparency_comments,
            'staff_retention_suggestions' => $request->staff_retention_suggestions,
            'clearance_status' => $request->clearance_status,
            'would_recommend_company' => $request->would_recommend_company,
            'recommendation_comments' => $request->recommendation_comments,
            'would_rejoin_future' => $request->would_rejoin_future,
            'what_liked_most' => $request->what_liked_most,
            'what_liked_least' => $request->what_liked_least,
            'suggestions_for_improvement' => $request->suggestions_for_improvement,
            'status' => 'draft'
        ]);

        return redirect()->route('exit-interview-feedbacks.show', $feedback)
            ->with('success', 'Exit interview feedback created successfully. You can review and submit it when ready.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExitInterviewFeedback $exitInterviewFeedback)
    {
        $exitInterviewFeedback->load(['employee.user', 'reviewedBy', 'department']);

        $user = Auth::user();

        // Allow access if:
        // 1. User is viewing their own feedback
        // 2. User has HR role or permission to review exit interviews
        // 3. User is the reviewer of this feedback

        $canView = false;

        // Check if user is viewing their own feedback
        if ($user->employee && $exitInterviewFeedback->employee_id === $user->employee->id) {
            $canView = true;
        }

        // Check if user has HR role or review permission
        if ($user->hasRole('human_resource') || $user->hasRole('hr') || $user->can('review-exit-interviews')) {
            $canView = true;
        }

        // Check if user is the reviewer
        if ($exitInterviewFeedback->reviewed_by === $user->id) {
            $canView = true;
        }

        // Check if user is admin or super admin
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            $canView = true;
        }

        if (! $canView) {
            abort(403, 'Unauthorized access. You do not have permission to view this feedback.');
        }

        return view('exit-interview-feedbacks.show', compact('exitInterviewFeedback'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExitInterviewFeedback $exitInterviewFeedback)
    {
        $user = Auth::user();

        // Only allow editing if:
        // 1. User is editing their own feedback AND it's in draft status
        // 2. User has HR role and feedback is in submitted status (for review/editing)

        $canEdit = false;

        // Check if user is editing their own feedback and it's in draft status
        if (
            $user->employee &&
            $exitInterviewFeedback->employee_id === $user->employee->id &&
            $exitInterviewFeedback->status === 'draft'
        ) {
            $canEdit = true;
        }

        // Check if user has HR role and can edit submitted feedback
        if (
            ($user->hasRole('human_resource') || $user->hasRole('hr') || $user->can('review-exit-interviews')) &&
            $exitInterviewFeedback->status === 'submitted'
        ) {
            $canEdit = true;
        }

        // Check if user is admin or super admin
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            $canEdit = true;
        }

        if (! $canEdit) {
            abort(403, 'Unauthorized access. You cannot edit this feedback.');
        }

        $departments = Department::where('for_school', 1)->get();
        return view('exit-interview-feedbacks.edit', compact('exitInterviewFeedback', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExitInterviewFeedback $exitInterviewFeedback)
    {
        $user = Auth::user();
        if (! $user || ! $user->employee) {
            abort(403, 'Employee record not found.');
        }

        // Ensure user can only update their own feedback and it's not submitted
        if (
            $exitInterviewFeedback->employee_id !== $user->employee->id ||
            $exitInterviewFeedback->status === 'submitted'
        ) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'last_working_day' => 'required|date|after_or_equal:today',
            'department_id' => 'required|exists:departments,id',
            'designation_role' => 'required|string|max:255',
            'date_of_joining' => 'required|date',
            'reporting_manager' => 'required|string|max:255',
            'reason_for_leaving_type' => 'required|string',
            'reason_for_leaving_other' => 'nullable|string|max:1000',
            'overall_job_satisfaction' => 'required|integer|min:1|max:5',
            'relationship_with_supervisor_rating' => 'required|integer|min:1|max:5',
            'relationship_with_supervisor_comments' => 'required|string|max:1000',
            'relationship_with_colleagues_rating' => 'required|integer|min:1|max:5',
            'relationship_with_colleagues_comments' => 'required|string|max:1000',
            'training_development_rating' => 'required|integer|min:1|max:5',
            'training_development_comments' => 'required|string|max:1000',
            'workload_worklife_balance_rating' => 'required|integer|min:1|max:5',
            'workload_worklife_balance_comments' => 'required|string|max:1000',
            'salary_benefits_satisfaction' => 'required|integer|min:1|max:5',
            'salary_benefits_comments' => 'required|string|max:1000',
            'performance_appraisal_fairness' => 'required|integer|min:1|max:5',
            'performance_appraisal_comments' => 'required|string|max:1000',
            'work_environment_rating' => 'required|integer|min:1|max:5',
            'work_environment_comments' => 'required|string|max:1000',
            'policies_procedures_rating' => 'required|integer|min:1|max:5',
            'policies_procedures_comments' => 'required|string|max:1000',
            'communication_transparency_rating' => 'required|integer|min:1|max:5',
            'communication_transparency_comments' => 'required|string|max:1000',
            'staff_retention_suggestions' => 'required|string|max:1000',
            'would_recommend_company' => 'required|boolean',
            'recommendation_comments' => 'required|string|max:1000',
            'would_rejoin_future' => 'required|boolean',
            'what_liked_most' => 'required|string|max:1000',
            'what_liked_least' => 'required|string|max:1000',
            'suggestions_for_improvement' => 'required|string|max:1000',
        ]);

        $exitInterviewFeedback->update($request->all());

        return redirect()->route('exit-interview-feedbacks.show', $exitInterviewFeedback)
            ->with('success', 'Exit interview feedback updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExitInterviewFeedback $exitInterviewFeedback)
    {
        $user = Auth::user();

        // Allow deletion if:
        // 1. User owns the feedback AND it's in draft status
        // 2. User has HR role or admin role

        $canDelete = false;

        // Check if user owns the feedback and it's in draft status
        if (
            $user->employee &&
            $exitInterviewFeedback->employee_id === $user->employee->id &&
            $exitInterviewFeedback->status === 'draft'
        ) {
            $canDelete = true;
        }

        // Check if user has HR role or admin role
        if (
            $user->hasRole('human_resource') || $user->hasRole('hr') ||
            $user->hasRole('admin') || $user->hasRole('super_admin') ||
            $user->can('review-exit-interviews')
        ) {
            $canDelete = true;
        }

        if (! $canDelete) {
            abort(403, 'Unauthorized access. You cannot delete this feedback.');
        }

        $exitInterviewFeedback->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Exit interview feedback deleted successfully.'
            ]);
        }

        return redirect()->route('exit-interview-feedbacks.index')
            ->with('success', 'Exit interview feedback deleted successfully.');
    }

    /**
     * Submit the feedback for review.
     */
    public function submit(ExitInterviewFeedback $exitInterviewFeedback)
    {
        $user = Auth::user();
        if (! $user || ! $user->employee) {
            abort(403, 'Employee record not found.');
        }

        // Ensure user can only submit their own feedback
        if ($exitInterviewFeedback->employee_id !== $user->employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $exitInterviewFeedback->update(['status' => 'submitted']);

        return redirect()->route('exit-interview-feedbacks.show', $exitInterviewFeedback)
            ->with('success', 'Exit interview feedback submitted successfully. HR will review it soon.');
    }

    /**
     * Show HR review interface.
     */
    public function review()
    {
        $user = Auth::user();

        // Check if user has permission to review exit interviews
        $canReview = false;

        // Check if user has HR role or review permission
        if (
            $user->hasRole('human_resource') || $user->hasRole('hr') ||
            $user->can('review-exit-interviews') || $user->hasRole('admin') ||
            $user->hasRole('super_admin')
        ) {
            $canReview = true;
        }

        if (! $canReview) {
            abort(403, 'Unauthorized access. You do not have permission to review exit interview feedbacks.');
        }

        $feedbacks = ExitInterviewFeedback::with(['employee.user', 'employee.designation', 'reviewedBy', 'department'])
            //->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('exit-interview-feedbacks.review', compact('feedbacks'));
    }

    /**
     * Mark feedback as reviewed by HR.
     */
    public function markReviewed(Request $request, ExitInterviewFeedback $exitInterviewFeedback)
    {
        $user = Auth::user();

        // Check if user has permission to review exit interviews
        $canReview = false;

        // Check if user has HR role or review permission
        if (
            $user->hasRole('human_resource') || $user->hasRole('hr') ||
            $user->can('review-exit-interviews') || $user->hasRole('admin') ||
            $user->hasRole('super_admin')
        ) {
            $canReview = true;
        }

        if (! $canReview) {
            abort(403, 'Unauthorized access. You do not have permission to review exit interview feedbacks.');
        }

        $request->validate([
            'hr_notes' => 'nullable|string|max:1000'
        ]);

        $exitInterviewFeedback->update([
            'status' => 'reviewed',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'hr_notes' => $request->hr_notes
        ]);

        return redirect()->route('exit-interview-feedbacks.review')
            ->with('success', 'Exit interview feedback marked as reviewed.');
    }
}
