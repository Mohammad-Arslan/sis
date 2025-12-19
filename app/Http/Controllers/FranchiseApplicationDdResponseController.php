<?php

namespace App\Http\Controllers;

use App\Models\FranchiseApplication;
use App\Models\FranchiseApplicationDdResponse;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use DataTables;

class FranchiseApplicationDdResponseController extends Controller
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
            $query = FranchiseApplicationDdResponse::with(['user'])->where('franchise_application_id', $data['franchise_application_id'])->get();

            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('review_by', function ($row) {
                    return $row['user']['name'];
                })
                ->addColumn('status', function ($row) {
                    return ucwords(str_replace('_', ' ', $row['status']));
                })
                ->addColumn('action', function ($row) {
                    return view('franchise_application.dd_response_action', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('franchise_application.franchise_application_dd_response', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($franchise_application_id)
    {
        $data['franchise_application_id'] = $franchise_application_id;
        $data['franchise_application'] = FranchiseApplication::find($data['franchise_application_id']);
        $data['review_by'] = Employee::with('user')->where('department_id', '=', 4)->orderBy('preferred_name')->get(['user_id','preferred_name'])->toArray();
        //$data['users'] = User::all();

        return view('franchise_application.franchise_application_dd_response', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $franchise_application_dd = FranchiseApplicationDDResponse::create($request->all());

        if ($request->ajax()) {
            return $franchise_application_dd;
        } else {
            return redirect()->back()->with('success', 'Application/Form submitted successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FranchiseApplicationDdResponse  $franchiseApplicationDd
     * @return \Illuminate\Http\Response
     */
    public function show(FranchiseApplicationDdResponse $franchiseApplicationDd)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FranchiseApplicationDdResponse  $franchiseApplicationDd
     * @return \Illuminate\Http\Response
     */
    public function edit(FranchiseApplicationDdResponse $franchiseApplicationDd)
    {
        $data['franchise_application_id'] = $franchiseApplicationDd->franchise_application_id;
        $data['franchise_application'] = FranchiseApplication::find($data['franchise_application_id']);
        $data['review_by'] = Employee::with('user')->where('department_id', '=', 4)->orderBy('preferred_name')->get(['user_id','preferred_name'])->toArray();
        $data['franchiseApplicationDd'] = $franchiseApplicationDd;

        return view('franchise_application.franchise_application_dd_response', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FranchiseApplicationDdResponse  $franchiseApplicationDd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FranchiseApplicationDdResponse $franchiseApplicationDd)
    {
        $franchiseApplicationDd->update($request->all());

        if ($request->ajax()) {
            return $franchiseApplicationDd;
        } else {
            return redirect()->route('franchise-application-dd.create', $franchiseApplicationDd->franchise_application_id)->with('success', 'Application/Form Updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FranchiseApplicationDdResponse  $franchiseApplicationDd
     * @return \Illuminate\Http\Response
     */
    public function destroy(FranchiseApplicationDdResponse $franchiseApplicationDd)
    {
        try {
            return $franchiseApplicationDd->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
