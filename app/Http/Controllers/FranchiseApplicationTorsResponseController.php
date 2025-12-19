<?php

namespace App\Http\Controllers;

use App\Models\FranchiseApplication;
use Illuminate\Database\QueryException;
use App\Models\FranchiseApplicationTorsResponse;
use App\Models\User;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class FranchiseApplicationTorsResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['franchise_application_id'] = $request->franchise_application_id;
        if ($request->ajax()) {
            $query = FranchiseApplicationTorsResponse::with([
                'reviewBy',
                'forwardedTo',
                'franchise_application.recommendedBy'
            ])->where('franchise_application_id', $data['franchise_application_id'])->get();

            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('review_by', function ($row) {
                    return ucwords($row['reviewBy']['name']);
                })
                ->addColumn('review_date', function ($row) {
                    return Carbon::parse($row->review_date)->format('d-m-Y');
                })
                ->addColumn('approval_date', function ($row) {
                    return isset($row->approval_date) ? Carbon::parse($row->approval_date)->format('d-m-Y') : '-';
                })
                ->addColumn('status', function ($row) {
                    return ucwords(str_replace('_', ' ', $row['status']));
                })
                ->addColumn('forwarded_to', function ($row) {
                    return ucwords($row['forwardedTo']['name']);
                })
                ->addColumn('recommended_by', function ($row) {
                    return isset($row['franchise_application']['recommendedBy']) ? ucwords($row['franchise_application']['recommendedBy']['name']) : '';
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    return view('franchise_application.tor_response_action', ['row' => $row]);
                })
                ->rawColumns(['recommended_by','action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($franchise_application_id)
    {
        $data['franchise_application'] = FranchiseApplication::with(['franchise_application_qa'])->where('id', $franchise_application_id)->first();
        $data['review_by'] = Employee::with('user')->where('department_id', 5)->whereNull('left_date')->orderBy('preferred_name')->get(['user_id','preferred_name'])->toArray();
        $data['forward_to'] = Employee::with('user')->where('branch_id', 2)->whereIn('department_id', [4,8,9])->whereNull('left_date')->orderBy('preferred_name')->get(['user_id','preferred_name'])->toArray();
        //dd($data['review_by']);
        return view('franchise_application.franchise_application_tors_response', $data);
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
            //'renovation_period' => 'required',
            //'new_renovate_date_from' => 'required',
            //'new_renovate_date_to' => 'required',
            //'agreement_date' => 'required',
            //'operational_date' => 'required',
            //'renewal_date' => 'required',
            //'bank_acc_opening_date' => 'required',
            //'review_date' => 'required',
        ]);

        $franchise_application_tors = FranchiseApplicationTorsResponse::create($request->all());

        if ($request->ajax()) {
            return $franchise_application_tors;
        } else {
            return redirect()->back()->with('success', 'Application/Form submitted successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FranchiseApplicationTorsResponse  $franchiseApplicationTor
     * @return \Illuminate\Http\Response
     */
    public function show(FranchiseApplicationTorsResponse $franchiseApplicationTor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FranchiseApplicationTorsResponse  $franchiseApplicationTor
     * @return \Illuminate\Http\Response
     */
    public function edit(FranchiseApplicationTorsResponse $franchiseApplicationTor)
    {
        $data['franchise_application'] = FranchiseApplication::with(['franchise_application_qa'])->where('id', $franchiseApplicationTor->franchise_application_id)->first();
        $data['review_by'] = Employee::with('user')->where('department_id', 5)->whereNull('left_date')->orderBy('preferred_name')->get(['user_id','preferred_name'])->toArray();
        $data['forward_to'] = Employee::with('user')->where('branch_id', 2)->whereIn('department_id', [4,8,9])->whereNull('left_date')->orderBy('preferred_name')->get(['user_id','preferred_name'])->toArray();
        $data['franchiseApplicationTors'] = $franchiseApplicationTor;

        return view('franchise_application.franchise_application_tors_response', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FranchiseApplicationTorsResponse  $franchiseApplicationTor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FranchiseApplicationTorsResponse $franchiseApplicationTor)
    {
        $franchiseApplicationTor->update($request->all());

        if ($request->ajax()) {
            return $franchiseApplicationTor;
        } else {
            return redirect()->back()->with('success', 'Application/Form Updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FranchiseApplicationTorsResponse  $franchiseApplicationTor
     * @return \Illuminate\Http\Response
     */
    public function destroy(FranchiseApplicationTorsResponse $franchiseApplicationTor)
    {
        try {
            return $franchiseApplicationTor->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
