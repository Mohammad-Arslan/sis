<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\NotifyMail;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\FranchiseApplication;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use App\Models\FrachiseApplicationRemark;
use App\Models\User;

class FrachiseApplicationRemarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request->all());
        if($request->ajax())
        {
            $data = FrachiseApplicationRemark::where('franchise_application_id', $request->franchise_application_id)->with(
                [
                    'franchise_application',
                    'user'
                ])->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('observation_for', function ($row) {
                return $row['observation_for'] ? ucwords(str_replace('_',' ',$row->observation_for)) : '';
            })
            ->addColumn('observation_by', function ($row) {
                $fullName = isset($row['user']) ? $row->user->first_name . ' ' . $row->user->last_name : '';
                return $fullName;
            })
            ->addColumn('observation_role', function ($row) {
                if(isset($row['user_role']) && $row['user_role'] =='super_admin')
                    return $row['user_role'] ? ucwords(str_replace('_',' ',$row->user_role)) : '';

                return $row['user_role'] ? ucwords(str_replace('-',' ',$row->user_role)) : '';
            })
            ->addColumn('created_at', function ($row) {
                return $row['created_at'] ? Carbon::parse($row['created_at'])->format('d-m-Y') : '';
            })
            ->addColumn('action', function ($row) {
                return view('franchise_application.observation_actions', ['row' => $row]);
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('franchise_application.application_observations');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'observation_for' => 'required',
            'observation' => 'required',
            'user_id' => 'required',
            'user_role' => 'required',
            'franchise_application_id' => 'required',
        ]);

        FrachiseApplicationRemark::create($request->all());

        $application = FranchiseApplication::where('id','=',$request->franchise_application_id)->with(['states', 'cities','franchise_application_qa'])->get()->toArray();
        $applicant = $application[0]['appl_name'].' '.$application[0]['appl_last_name'];
        $applicant_email = $application[0]['email'];
        $city = $application[0]['cities']['city_name'];
        $state = $application[0]['states']['state_name'];
        $agreement_type = $application[0]['agreement_type'];
        $proposed_location = $application[0]['franchise_application_qa']['proposed_location'];

        $sender_name = User::where('id',$request->user_id)->first('name');

        $subject = 'New Observation has been submitted for Franchise Application';
        $type = null; $receiver = $receivers_emails = [];
        if($request->observation_for == 'BD')
        {
            $type = 'Business Development';
            $bd_reps = Employee::where('department_id','=','5')->where('branch_id','=','2')->whereNull('left_date')->pluck('user_id')->toArray();
            $receiver =  User::WhereIn('id',$bd_reps)->pluck('email')->toArray();
        }
        elseif($request->observation_for == 'QA_Report')
        {
            $type = 'QA Report';
            $qa_reps = Employee::where('department_id','=','6')->where('branch_id','=','2')->whereNull('left_date')->pluck('user_id')->toArray();
            $receiver =  User::WhereIn('id',$qa_reps)->pluck('email')->toArray();

        }
        elseif($request->observation_for == 'Document')
        {
            $type = 'Document';
            $user_reps = Employee::WhereIn('department_id',[6,9])->where('branch_id','=','2')->whereNull('left_date')->pluck('user_id')->toArray();
            $receiver =  User::WhereIn('id',$user_reps)->pluck('email')->toArray();
        }
        elseif($request->observation_for == 'IASF')
        {
            $type = 'IASF';
            $user_reps = Employee::WhereIn('department_id',[5,6,9])->where('branch_id','=','2')->whereNull('left_date')->pluck('user_id')->toArray();
            $receiver = User::WhereIn('id',$user_reps)->pluck('email')->toArray();
        }
        elseif($request->observation_for == 'TOR')
        {
            $type = 'TOR';
            $user_reps = Employee::WhereIn('department_id',[6,9])->where('branch_id','=','2')->whereNull('left_date')->pluck('user_id')->toArray();
            $receiver = User::WhereIn('id',$user_reps)->pluck('email')->toArray();
        }
        else
        {
            $type = $receiver = null;
        }


            if(isset($type) && isset($receiver))
            {
                $cc=['zahid.kazmi@ucs.edu.pk','saba.qureshi@bh.edu.pk'];
                $receiver_name = ' Concerns';
                $message = 'Kindly login to the OMS (Online Management System) to initiate the necessary steps for reviewing the observation related to '.$type.'. This is in regard to the franchise application mentioned below.<br>
                    Application for:<br>
                    NWA Name :'.$applicant.'<br>
                    City :'.$city.',<br>
                    Agreement Type :'.$agreement_type.'<br>
                    Site Address :'.$proposed_location.'<br>
                    Observation: '.$request->observation.'<br>
                    From<br>
                    '.$sender_name->name.'<br>
                    Date: '.date('d-M-Y');

                Mail::to($receiver)->cc($cc)->send(new NotifyMail($subject, $receiver_name, $message));
                    return redirect()->route('application_observations', ['franchise_application_id' => $request->franchise_application_id])
                        ->with('success', 'Observation has been created successfully.');
            }
            else
            {
                return redirect()->route('application_observations', ['franchise_application_id' => $request->franchise_application_id])
                        ->with('success', 'Observation has been created successfully.');
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FrachiseApplicationRemark  $frachiseApplicationRemark
     * @return \Illuminate\Http\Response
     */
    public function show(FrachiseApplicationRemark $frachiseApplicationRemark)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FrachiseApplicationRemark  $frachiseApplicationRemark
     * @return \Illuminate\Http\Response
     */
    public function edit(FrachiseApplicationRemark $frachiseApplicationRemark)
    {
        return view('franchise_application.application_observations',['franchise_application_id' => $frachiseApplicationRemark->franchise_application_id, 'frachiseApplicationRemark' => $frachiseApplicationRemark]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FrachiseApplicationRemark  $frachiseApplicationRemark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FrachiseApplicationRemark $frachiseApplicationRemark)
    {
        $request->validate([
            'observation_for' => 'required',
            'observation' => 'required',
            'user_id' => 'required',
            'user_role' => 'required',
            'franchise_application_id' => 'required',
        ]);


        $frachiseApplicationRemark->update($request->all());
        return redirect()->route('application_observations',['franchise_application_id' => $request->franchise_application_id])
            ->with('success', 'Observation has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FrachiseApplicationRemark  $frachiseApplicationRemark
     * @return \Illuminate\Http\Response
     */
    public function destroy(FrachiseApplicationRemark $frachiseApplicationRemark)
    {
        try {
            return $frachiseApplicationRemark->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
