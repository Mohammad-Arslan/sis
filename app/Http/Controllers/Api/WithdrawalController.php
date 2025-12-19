<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentWithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\WithdrawalReason;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class WithdrawalController extends Controller
{
    public function getWithdrawalReasons()
    {
        $withdrawalReasons = WithdrawalReason::all();

        return response($withdrawalReasons, 200);
    }

    public function setWithdrawalRequest(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
            //'student_id' => 'required|unique:student_withdrawals|unique:student_withdrawal_requests',//unique:student_withdrawal_requests',//unique:student_withdrawals|
            'student_id' => 'required',
            'guardian_id' => 'required',
            'beneficiary_name' => 'required',
            'last_day_at_school' => 'required',
            'withdrawal_reason_id' => 'required',
            //'guardian_cnic_image_front' => 'required',//|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            //'guardian_cnic_image_back' => 'required',//|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ],
            [ 'student_id.unique' => 'The :attribute already exists for withdrawal.']
        );

        if ($validator->fails()) {
            return response($validator->errors()->all(), Response::HTTP_BAD_REQUEST);
        }

        //Create new record
        $studentWithdrawalRequest = StudentWithdrawalRequest::create($request->all());

        //Get student id
        //$guardianId = $request->input('guardian_id');
        $studentId = $request->input('student_id');
        $destinationPath = 'guardian_docs/' . $studentId . '/';

        if ($request->hasfile('guardian_cnic_image_front')) {
            //Remove previous folder if already exists
            if (Storage::disk('s3')->exists($destinationPath)) {
                Storage::disk('s3')->delete($destinationPath);
            }

            $extension = $request->guardian_cnic_image_front->getClientOriginalExtension();
            $fileBasename = basename($request->guardian_cnic_image_front->getClientOriginalName(), '.' . $extension);
            //$filename = $request->guardian_cnic_image_front->getClientOriginalName();

            $filename = $fileBasename . '-' . time() . '.' . $extension;

            //$input['file_name'] = $filename;
            //$filepath = 'guardian_docs/' . $guardianId.'/'. $filename;
            //Storage::disk('s3')->put($filepath, file_get_contents($request->guardian_cnic_image_front));

            //Move Uploaded File
            //$destinationPath = $awsPreviousPath;//'uploads';
            //$filesystem = Storage::disk('local');
            $request->guardian_cnic_image_front->move($destinationPath, $filename);

            //$filesystem->putFileAs($destinationPath, $request->guardian_cnic_image_front, $filename);
            //dd($filesystem);

            $studentWithdrawalRequest->guardian_cnic_image_front = $destinationPath . $filename;
            $studentWithdrawalRequest->save();

            //return response(['Record added & file successfully uploaded!'], Response::HTTP_CREATED);
        }

        if ($request->hasfile('guardian_cnic_image_back')) {
            //Remove previous folder if already exists
            if (Storage::disk('s3')->exists($destinationPath)) {
                Storage::disk('s3')->delete($destinationPath);
            }

            $extension = $request->guardian_cnic_image_back->getClientOriginalExtension();
            $fileBasename = basename($request->guardian_cnic_image_back->getClientOriginalName(), '.' . $extension);
            //$filename = $request->guardian_cnic_image_back->getClientOriginalName();

            $filename = $fileBasename . '-' . time() . '.' . $extension;

            //$input['file_name'] = $filename;
            //$filepath = 'guardian_docs/' . $guardianId.'/'. $filename;
            //Storage::disk('s3')->put($filepath, file_get_contents($request->guardian_cnic_image_back));

            //Move Uploaded File
            //$destinationPath = $awsPreviousPath;//'uploads';
            //$filesystem = Storage::disk('local');
            $request->guardian_cnic_image_back->move($destinationPath, $filename);

            //$filesystem->putFileAs($destinationPath, $request->guardian_cnic_image_back, $filename);
            //dd($filesystem);

            $studentWithdrawalRequest->guardian_cnic_image_back = $destinationPath . $filename;
            $studentWithdrawalRequest->save();

            return response(['Record added & file successfully uploaded!'], Response::HTTP_CREATED);
        }

        return response(['Record successfully created!'], Response::HTTP_CREATED);
    }
}
