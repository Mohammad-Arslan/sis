<?php

namespace App\Http\Controllers;

use App\Models\GuardianInfoUpdate;
use App\Models\Guardian;
use App\Models\StudentAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Session;
use DataTables;

class GuardianInfoUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*if ($request->ajax()){
            $guardianRequests = GuardianInfoUpdate::with([
                'guardian'
            ]);

            $guardianRequests = $this->filteration($request,$guardianRequests);
            $guardianRequests = $this->paginateData($guardianRequests,$request);

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($guardianRequests['totalData']),
                "recordsFiltered" => intval($guardianRequests['totalFiltered']),
                "data"            => $guardianRequests['data']
            );
            dd($json_data);
            return response()->json($json_data);
        }

        $data['guardian_requests'] = GuardianInfoUpdate::all();

        return view('guardian_info_update.index',$data);*/


        if ($request->ajax()) {

            $data = GuardianInfoUpdate::with([
                'guardian', 'student.student_address'
            ])->where('status', 'pending');

            if (!isSuperAdmin() && !isHeadOfficeEmp() /*!auth()->user()->hasRole('manager-parent-relations')*/) {
                $data->whereHas('student', function ($q) {
                    $q->where('branch_id', get_branch_id());
                });
            }
            $data->get();
            // dd($data->toArray());

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('guardian_info_update.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $guardianRequests = GuardianInfoUpdate::with([
            'guardian'
        ])->get();

        return view('guardian_info_update.index', compact('guardianRequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        echo 'in create';
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupportQuery  $supportQuery
     * @return \Illuminate\Http\Response
     */
    public function show(GuardianInfoUpdate $guardianInfoUpdate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupportQuery  $supportQuery
     * @return \Illuminate\Http\Response
     */
    public function edit(GuardianInfoUpdate $guardianInfoUpdate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportQuery  $supportQuery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GuardianInfoUpdate $guardianInfoUpdate)
    {

        //Get guardian update type, what needs to be updated
        $guardianInfoUpdateType = $guardianInfoUpdate->update_type;

        //Get guardian id from current record
        $guardianId = $guardianInfoUpdate->guardian_id;

        //Get guardian record
        $guardianRecord = Guardian::where('id', $guardianId)->first();

        if ($guardianInfoUpdate->student_id)
            // Get student address
            $studentAddress = StudentAddress::where('student_id', $guardianInfoUpdate->student_id)->first();
        //Check if record exits before update
        if ($guardianRecord) {

            if ($guardianInfoUpdateType == 'email') {
                //Update guardian actual email with the provided one
                $guardianRecord->email = $guardianInfoUpdate->update_value;
            } else if ($guardianInfoUpdateType == 'phone') {
                //Update guardian actual phone with the provided one
                $guardianRecord->mobile = $guardianInfoUpdate->update_value;
            } else if ($guardianInfoUpdateType ==  'cors_mobile') {
                //Update student actual phone with the provided one
                $studentAddress->per_phone = $guardianInfoUpdate->update_value;
            } else if ($guardianInfoUpdateType ==  'cors_address') {
                //Update student actual address with the provided one
                $studentAddress->per_address = $guardianInfoUpdate->update_value;
            }

            //Save actual guardian record
            $guardianRecord->save();

            //Save actual corespondence address record
            if ($guardianInfoUpdate->student_id)
                $studentAddress->save();
            //Change status to completed
            $guardianInfoUpdate->status = 'completed';
            $guardianInfoUpdate->save();
        }

        Session::flash('success', 'Guardian info updated successfully.');

        return response()->json(['code' => 200, 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GuardianInfoUpdate  $GuardianInfoUpdate
     * @return \Illuminate\Http\Response
     */
    public function destroy(GuardianInfoUpdate $guardianInfoUpdate)
    {
        try {

            //Remove record from database
            $guardianInfoUpdate->delete();

            Session::flash('success', 'Guardian info removed successfully.');

            return response()->json(['code' => 200, 'status' => 'success']);
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
