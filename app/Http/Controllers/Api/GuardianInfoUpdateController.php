<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FamilyInformation;
use App\Models\Guardian;
use App\Models\GuardianOtp;
use Illuminate\Http\Request;
use App\Models\GuardianInfoUpdate;
use App\Models\Student;
use App\Models\StudentAddress;

class GuardianInfoUpdateController extends Controller
{
    public function getGuardiansInfo(Request $request)
    {
        $student = Student::where('id', $request->student_id)->with(['guardians.relation', 'student_address'])->get();
        return response($student, 200);
    }

    public function guardianInfoChangeRequest(Request $request)
    {
        $changeDataRequest = $request->validate([
            'guardian_id' => 'required|integer',
            'update_type' => 'in:email,phone,cors_mobile,cors_address',
            'update_value' => 'required',
            'student_id' => 'required_if:update_type,cors_mobile,cors_address'
        ]);

        //Check if request is validated
        if ($changeDataRequest) {
            //Get request params
            $updateType = $request->input('update_type');
            $updateValue = $request->input('update_value');
            $guardianId = $request->input('guardian_id');

            //Check if request not already exits
            $checkInfoAlready = GuardianInfoUpdate::where('update_type', $updateType)
                ->where('guardian_id', $guardianId)
                ->where('status', 'pending')
                ->first();

            //Check if record not already exists
            if (! $checkInfoAlready) {
                //Check if email or password not already exits for any other guardian/parent
                if ($updateType == 'email') {
                    $checkRecordAlreadyExists = Guardian::where('email', $updateValue)->first();
                } else if ($updateType == 'phone') {
                    $checkRecordAlreadyExists = Guardian::where('mobile', $updateValue)->first();
                } else if ($updateType == 'cors_mobile') {
                    $checkRecordAlreadyExists = StudentAddress::where('per_phone', $updateValue)->first();
                } else if ($updateType == 'cors_address') {
                    $checkRecordAlreadyExists = StudentAddress::where('per_address', $updateValue)->first();
                } else {
                    $checkRecordAlreadyExists = false;
                }

                //If provided email or phone already exists for guardian, throw error
                if ($checkRecordAlreadyExists) {
                    return response('Your provided ' . $updateType . ' already exists in our system, please user other!', 400);
                } else {
                    //Create record
                    GuardianInfoUpdate::create($changeDataRequest);
                }
            } else {
                return response('You\'ve already requested to change your ' . $updateType . '!', 400);
            }

            //Send response to client for suceess
            return response('Record has been added successfully!', 200);
        }
        return response('Invalid request!', 400);
    }
}
