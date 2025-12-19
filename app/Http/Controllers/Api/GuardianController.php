<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FamilyInformation;
use App\Models\FcmToken;
use App\Models\Guardian;
use App\Models\GuardianOtp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Student;
use Log;

// Added this import for the new login method

class GuardianController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            // First, try to find the guardian with basic info
            $user = Guardian::where('email', $request->email)->first();

            if (! $user) {
                return response()->json(['message' => 'No guardian found with this email'], 400);
            }

            // Check if any guardian with this email has active students
            $hasActiveStudents = false;
            $activeStudents = collect();

            // Get all guardians with the same email
            $allGuardians = Guardian::where('email', $request->email)
                ->where('deleted_at', null)
                ->get();

            foreach ($allGuardians as $guardian) {
                // Check direct student relationship (if guardian is directly linked to a student)
                if ($guardian->student_id) {
                    $directStudent = Student::where('id', $guardian->student_id)
                        ->where('status', 'on_roll')
                        ->first();
                    if ($directStudent) {
                        $hasActiveStudents = true;
                        $activeStudents->push($directStudent);
                    }
                }

                // Check family students
                $familyStudents = $guardian->familyStudents()
                    ->where('status', 'on_roll')
                    ->get();
                if ($familyStudents->count() > 0) {
                    $hasActiveStudents = true;
                    $activeStudents = $activeStudents->merge($familyStudents);
                }
            }

            if (! $hasActiveStudents) {
                return response()->json([
                    'message' => 'No active students found for this guardian',
                    'debug_info' => [
                        'guardian_id' => $user->id,
                        'direct_student_id' => $user->student_id,
                        'has_family' => $user->family ? 'yes' : 'no'
                    ]
                ], 400);
            }

            // Load relationships for the response
            $user->load(['relation', 'family.children.student']);

            $randomNumber = random_int(1000, 9999);
            $randomNumber = $request->email == 'test@example.com' ? 1234 : $randomNumber;

            // Send OTP via email instead of SMS
            $this->sendOTPEmail($user->email, $randomNumber);

            $token = $user->createToken('myapptoken')->plainTextToken;

            if ($request->email == 'test@example.com') {
                GuardianOtp::where('guardian_id', '=', $user->id)->update([
                    'status' => 'PENDING'
                ]);
            } else {
                GuardianOtp::create([
                    'guardian_id' => $user->id,
                    'OTP' => $randomNumber,
                    'status' => 'PENDING'
                ]);
            }

            $response = [
                'token' => $token,
                'code' => $randomNumber,
                'guardian_info' => [
                    'id' => $user->id,
                    'name' => $user->guardian_name,
                    'email' => $user->email,
                    'active_students_count' => $activeStudents->count()
                ]
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            Log::error('Guardian login error: ' . $e->getMessage(), [
                'email' => $request->email,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred during login',
                'debug_info' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function verifyOTP(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'OTP' => 'required|integer',
        ]);
        $guardian = Guardian::where('id', $user->id)->with('relation')->first();

        if ($user) {
            $otp = GuardianOtp::where(['guardian_id' => $user->id, 'otp' => $request->OTP, 'status' => 'PENDING'])->first();
            if ($otp) {
                // Load family information with proper relationships
                $childs = FamilyInformation::where('guardian_id', $user->id)
                    ->with(['children.student' => function ($query) {
                        $query->where('status', 'on_roll');
                    }, 'children.student.active_class.academic_years', 'children.student.active_class.branch_class_sections.com_classes', 'children.student.active_class.branch_class_sections.branches', 'children.student.active_class.branch_class_sections.sections'])
                    ->get();

                $otp->status = 'COMPLETE';
                $otp->save();

                return response([
                    'user' => $guardian,
                    'childs' => $childs,
                ], 200);
            }
            return response()->json(['message' => 'Invalid User OTP'], 400);
        }
        return response()->json(['message' => 'Invalid User'], 400);
    }

    public function refreshInfo(Request $request)
    {
        $user = $request->user();
        $guardian = Guardian::where('id', $user->id)->with('relation')->first();
        if ($user) {
            // Load family information with proper relationships
            $childs = FamilyInformation::where('guardian_id', $user->id)
                ->with(['children.student' => function ($query) {
                    $query->where('status', 'on_roll');
                }, 'children.student.active_class.academic_years', 'children.student.active_class.branch_class_sections.com_classes', 'children.student.active_class.branch_class_sections.branches', 'children.student.active_class.branch_class_sections.sections'])
                ->get();

            return response([
                'user' => $guardian,
                'childs' => $childs,
            ], 200);
        }
        return response('Invalid User', 400);
    }

    public function resendOTP(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $otps = GuardianOtp::where(['guardian_id' => $user->id, 'status' => 'PENDING'])->get();
            if ($otps) {
                foreach ($otps as $otp) {
                    $otp->status = 'REJECT';
                    $otp->save();
                }
            }
            $randomNumber = random_int(1000, 9999);

            // Send OTP via email instead of SMS
            $this->sendOTPEmail($user->email, $randomNumber);

            GuardianOtp::create([
                'guardian_id' => $user->id,
                'OTP' => $randomNumber,
                'status' => 'PENDING'
            ]);

            $response = [
                'code' => $randomNumber
            ];
            return response()->json(['message' => 'OTP sent'], 200);
        }
        return response()->json(['message' => 'Invalid User'], 400);
    }

    public function storeFCM(Request $request)
    {
        $user = $request->user();
        $fcmToken = FcmToken::where([
            'guardian_id' => $user->id,
            'device_key' => $request->token
        ])->get();
        if (count($fcmToken) > 0) {
            return response()->json(['message' => 'Token Already exists'], 200);
        }
        FcmToken::create([
            'guardian_id' => $user->id,
            'device_key' => $request->token,
        ]);
        return response()->json(['message' => 'Token saved'], 200);
    }

    public function siblings_data(Request $request)
    {
        $guardian = $request->user();
        $siblings = FamilyInformation::where('guardian_id', $guardian->id)->with(['children.student' => function ($query) {
            $query->where('status', 'on_roll');
        }, 'children.student.active_class.academic_years', 'children.student.active_class.branch_class_sections.com_classes', 'children.student.active_class.branch_class_sections.branches', 'children.student.active_class.branch_class_sections.sections'])->get();
        return response()->json($siblings, 200);
    }

    /**
     * Send OTP via email
     */
    private function sendOTPEmail($email, $otp)
    {
        $subject = 'Your OTP for New Device Login';
        $message = "Your OTP for New Device Login is: {$otp}";

        // You can use Laravel's Mail facade or create a custom email template
        // For now, using a simple approach - you may want to create a proper email template
        Mail::raw($message, function ($message) use ($email, $subject) {
            $message->to($email)
                    ->subject($subject);
        });
    }
}
