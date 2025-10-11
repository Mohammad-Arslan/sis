<?php

namespace App\Http\Controllers;

use App\Models\EmploymentLetterRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class EmploymentLetterRequestController extends Controller
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
        if (!$user || !$user->employee) {
            abort(403, 'Employee record not found.');
        }

        $requests = EmploymentLetterRequest::with(['employee.user', 'employee.designation', 'approvedBy'])
            ->where('employee_id', $user->employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employment-letter-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employment-letter-requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->employee) {
            abort(403, 'Employee record not found.');
        }

        $request->validate([
            'request_type' => 'required|string|in:employment_letter,experience_letter',
            'purpose' => 'required|string|max:500',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        EmploymentLetterRequest::create([
            'employee_id' => $user->employee->id,
            'request_type' => $request->request_type,
            'purpose' => $request->purpose,
            'additional_notes' => $request->additional_notes,
            'status' => 'pending',
        ]);

        return redirect()->route('employment-letter-requests.index')
            ->with('success', 'Employment letter request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmploymentLetterRequest $employmentLetterRequest)
    {
        $user = Auth::user();
        if (!$user || !$user->employee) {
            abort(403, 'Employee record not found.');
        }

        // Ensure user can only view their own requests or has approval permission
        if ($employmentLetterRequest->employee_id !== $user->employee->id && 
            !$user->hasPermission('employment-letter-approval')) {
            abort(403, 'Unauthorized access.');
        }

        $employmentLetterRequest->load(['employee.user', 'employee.designation', 'approvedBy']);
        
        return view('employment-letter-requests.show', compact('employmentLetterRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmploymentLetterRequest $employmentLetterRequest)
    {
        $user = Auth::user();
        if (!$user || !$user->employee) {
            abort(403, 'Employee record not found.');
        }

        // Only allow editing if pending and user owns the request
        if ($employmentLetterRequest->employee_id !== $user->employee->id || 
            $employmentLetterRequest->status !== 'pending') {
            abort(403, 'Unauthorized access.');
        }

        return view('employment-letter-requests.edit', compact('employmentLetterRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmploymentLetterRequest $employmentLetterRequest)
    {
        $user = Auth::user();
        if (!$user || !$user->employee) {
            abort(403, 'Employee record not found.');
        }

        // Only allow updating if pending and user owns the request
        if ($employmentLetterRequest->employee_id !== $user->employee->id || 
            $employmentLetterRequest->status !== 'pending') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'request_type' => 'required|string|in:employment_letter,experience_letter',
            'purpose' => 'required|string|max:500',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        $employmentLetterRequest->update($request->only(['request_type', 'purpose', 'additional_notes']));

        return redirect()->route('employment-letter-requests.index')
            ->with('success', 'Employment letter request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmploymentLetterRequest $employmentLetterRequest)
    {
        $user = Auth::user();
        if (!$user || !$user->employee) {
            abort(403, 'Employee record not found.');
        }

        // Only allow deletion if pending and user owns the request
        if ($employmentLetterRequest->employee_id !== $user->employee->id || 
            $employmentLetterRequest->status !== 'pending') {
            abort(403, 'Unauthorized access.');
        }

        $employmentLetterRequest->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Employment letter request deleted successfully.'
            ]);
        }

        return redirect()->route('employment-letter-requests.index')
            ->with('success', 'Employment letter request deleted successfully.');
    }

    /**
     * Show approval interface for HR
     */
    public function approval()
    {
        // $this->authorize('employment-letter-approval');

        $requests = EmploymentLetterRequest::with(['employee.user', 'employee.designation', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('employment-letter-requests.approval', compact('requests'));
    }

    /**
     * Approve or reject a request
     */
    public function approve(Request $request, EmploymentLetterRequest $employmentLetterRequest)
    {
        // $this->authorize('employment-letter-approval');

        $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|string|max:500',
        ]);

        if ($request->action === 'approve') {
            // Load the necessary relationships before generating letter content
            $employmentLetterRequest->load(['employee.user', 'employee.designation']);
            
            $employmentLetterRequest->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'letter_content' => $this->generateLetterContent($employmentLetterRequest),
            ]);

            // Generate PDF and store
            $pdfPath = $this->generateLetterPDF($employmentLetterRequest);
            $employmentLetterRequest->update(['letter_file_path' => $pdfPath]);

            return redirect()->back()->with('success', 'Request approved successfully.');
        } else {
            $employmentLetterRequest->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            return redirect()->back()->with('success', 'Request rejected successfully.');
        }
    }

    /**
     * Download the generated letter
     */
    public function download(EmploymentLetterRequest $employmentLetterRequest)
    {
        $user = Auth::user();
        if (!$user || !$user->employee) {
            abort(403, 'Employee record not found.');
        }

        // Ensure user can only download their own approved letters or has approval permission
        if ($employmentLetterRequest->employee_id !== $user->employee->id && 
            !$user->hasPermission('employment-letter-approval')) {
            abort(403, 'Unauthorized access.');
        }

        if ($employmentLetterRequest->status !== 'approved' || !$employmentLetterRequest->letter_file_path) {
            abort(404, 'Letter not available.');
        }

        return Storage::download($employmentLetterRequest->letter_file_path);
    }

    /**
     * Generate letter content
     */
    private function generateLetterContent(EmploymentLetterRequest $request)
    {
        $employee = $request->employee;
        
        if (!$employee) {
            throw new \Exception('Employee record not found for this request.');
        }
        
        // Get employee name with prefix
        $prefix = $employee->prefix ?? '';
        $preferredName = $employee->preferred_name ?? '';
        $fullName = $preferredName ? $preferredName : (($employee->first_name ?? '') . ' ' . ($employee->last_name ?? ''));
        $displayName = $prefix ? $prefix . ' ' . $fullName : $fullName;
        
        // Get father name with gender-appropriate terminology
        $fatherName = $employee->father_name ?? '';
        $fatherNameText = '';
        if ($fatherName) {
            // Determine gender from user table
            $gender = strtolower(trim(($employee->user->gender ?? '') ?: ''));
            if (in_array($gender, ['male', 'm', 'man'])) {
                $fatherNameText = " (S/O {$fatherName})"; // Son of
            } elseif (in_array($gender, ['female', 'f', 'woman'])) {
                $fatherNameText = " (D/O {$fatherName})"; // Daughter of
            } else {
                // Fallback to prefix-based logic if gender is not available
                $prefix = strtolower(trim($employee->prefix ?? ''));
                if (in_array($prefix, ['mr', 'mister'])) {
                    $fatherNameText = " (S/O {$fatherName})"; // Son of
                } elseif (in_array($prefix, ['mrs', 'ms', 'miss'])) {
                    $fatherNameText = " (D/O {$fatherName})"; // Daughter of
                } else {
                    $fatherNameText = " (S/O {$fatherName})"; // Default to Son of
                }
            }
        }
        
        // Get CNIC from user table
        $cnic = $employee->user->CNIC ?? '';
        $cnicText = $cnic ? "CNIC: {$cnic}" : "Employee ID: {$employee->id}";
        
        // Get designation
        $designation = 'Employee'; // Default fallback
        if ($employee->designation) {
            $designation = $employee->designation->designation_name ?? 'Employee';
        }
        
        // Debug: Log designation info
        \Log::info('Designation Debug', [
            'employee_id' => $employee->id,
            'designation_loaded' => $employee->relationLoaded('designation'),
            'designation_id' => $employee->designation_id,
            'designation_name' => $employee->designation->designation_name ?? 'null',
            'final_designation' => $designation
        ]);
        
        // Get employment start date
        $startDate = $employee->created_at ? $employee->created_at->format('F d, Y') : 'Unknown';
        
        // Get employment end date based on letter type and employee status
        $endDate = 'present';
        if ($request->request_type === 'experience_letter' && $employee->left_date) {
            $endDate = $employee->left_date->format('F d, Y');
        }
        
        // Get the logo path and convert to base64 for PDF compatibility
        $logoPath = public_path('assets/img/1x/ucs_logo.png');
        $logoExists = file_exists($logoPath);
        $logoBase64 = '';
        
        if ($logoExists) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }
        
        $content = "
        <div style='font-family: Arial, sans-serif; line-height: 1.8; max-width: 800px; margin: 0 auto; padding: 40px; position: relative;'>
            " . ($logoExists ? "
            <div style='position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); opacity: 0.05; z-index: -1; pointer-events: none;'>
                <img src='{$logoBase64}' style='width: 600px; height: auto;' alt='UCS Logo'>
            </div>
            " : "") . "
            <div style='text-align: center; margin-bottom: 40px;'>
                " . ($logoExists ? "
                <div style='margin-bottom: 20px;'>
                    <img src='{$logoBase64}' style='width: 120px; height: auto;' alt='UCS Logo'>
                </div>
                " : "") . "
                <h1 style='color: #2c3e50; font-size: 28px; margin-bottom: 10px; font-weight: bold;'>" . strtoupper(str_replace('_', ' ', $request->request_type)) . "</h1>
                <div style='border-bottom: 2px solid #3498db; width: 200px; margin: 0 auto;'></div>
            </div>
            
            <div style='text-align: right; margin-bottom: 30px; font-size: 14px; color: #666;'>
                <strong>Date: " . now()->format('F d, Y') . "</strong>
            </div>
            
            <div style='margin-bottom: 20px;'>
                <p style='font-size: 16px; margin-bottom: 20px;'>To Whom It May Concern,</p>
            </div>
            
            <div style='margin-bottom: 25px; text-align: justify;'>";

        // Generate content based on letter type
        if ($request->request_type === 'experience_letter') {
            $content .= "
                <p style='font-size: 16px; line-height: 1.8; margin-bottom: 15px;'>
                    This is to certify that <strong>{$displayName}{$fatherNameText}</strong> 
                    ({$cnicText}) worked with our organization 
                    from <strong>{$startDate}</strong> to <strong>{$endDate}</strong>.
                </p>
                
                <p style='font-size: 16px; line-height: 1.8; margin-bottom: 15px;'>
                    During this period, {$fullName} served as <strong>{$designation}</strong> 
                    and has been found to be honest, hardworking, and dedicated to their duties.
                </p>
                
                <p style='font-size: 16px; line-height: 1.8; margin-bottom: 15px;'>
                    We wish {$fullName} all the best in their future endeavors.
                </p>";
        } else {
            // Employment letter (default)
            $content .= "
                <p style='font-size: 16px; line-height: 1.8; margin-bottom: 15px;'>
                    This is to certify that <strong>{$displayName}{$fatherNameText}</strong> 
                    ({$cnicText}) has been employed with our organization 
                    from <strong>{$startDate}</strong> to present.
                </p>
                
                <p style='font-size: 16px; line-height: 1.8; margin-bottom: 15px;'>
                    During this period, {$fullName} has served as <strong>{$designation}</strong> 
                    and has been found to be honest, hardworking, and dedicated to their duties.
                </p>
                
                <p style='font-size: 16px; line-height: 1.8; margin-bottom: 15px;'>
                    We wish {$fullName} all the best in their future endeavors.
                </p>";
        }

        $content .= "
            </div>
            
            <div style='margin-top: 50px;'>
                <p style='font-size: 16px; margin-bottom: 10px;'>Sincerely,</p>
                <p style='font-size: 16px; font-weight: bold; margin-bottom: 5px;'>Human Resources Department</p>
                
                <div style='margin-top: 20px;'>
                    <div style='border-bottom: 1px solid #333; width: 200px; margin-bottom: 5px;'></div>
                    <p style='font-size: 14px; margin: 0; color: #666;'>Authorized Signature</p>
                </div>
                
                <p style='font-size: 16px; font-weight: normal; color: #2c3e50; margin-top: 20px;'>" . config('app.name') . "</p>
            </div>
            
            <div style='margin-top: 60px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;'>
                <p style='font-size: 12px; color: #888; font-style: italic;'>
                    This is a system-generated document and does not require a manual signature or stamp.
                </p>
            </div>
        </div>";

        return $content;
    }

    /**
     * Generate PDF file
     */
    private function generateLetterPDF(EmploymentLetterRequest $request)
    {
        if (!$request->letter_content) {
            throw new \Exception('Letter content is required to generate PDF.');
        }
        
        $pdf = Pdf::loadHTML($request->letter_content);
        $filename = 'employment_letter_' . ($request->id ?? 'unknown') . '_' . time() . '.pdf';
        $path = 'employment-letters/' . $filename;
        
        Storage::put($path, $pdf->output());
        
        return $path;
    }
}
