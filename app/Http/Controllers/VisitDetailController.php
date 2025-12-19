<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\City;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\VisitDetail;
use Illuminate\Http\Request;
use App\Models\CampusOfficeType;
use Illuminate\Database\QueryException;

class VisitDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->req == 'my') {
                $data = VisitDetail::where('user_id', auth()->user()->id)->with('branch', 'user', 'campus', 'fromCity', 'toCity', 'approvedBy');
            } elseif ($request->req == 'me') {
                $data = VisitDetail::where('approved_by', auth()->user()->id)->with('branch', 'user', 'campus', 'fromCity', 'toCity', 'approvedBy');
            } else {
                $data = VisitDetail::with('branch', 'user', 'campus', 'fromCity', 'toCity', 'approvedBy');
            }

            if ($request->branch_id && $request->branch_id > 0) {
                $data = $data->where('branch_id', $request->branch_id);
            }
            if ($request->from_city_id && $request->from_city_id > 0) {
                $data = $data->where('from_city_id', $request->from_city_id);
            }
            if ($request->to_city_id && $request->to_city_id > 0) {
                $data = $data->where('to_city_id', $request->to_city_id);
            }
            if ($request->total_duration && $request->total_duration > 0) {
                $data = $data->where('total_duration', $request->total_duration);
            }
            if ($request->campus_office_id && $request->campus_office_id > 0) {
                $data = $data->where('campus_office_id', $request->campus_office_id);
            }
            if ($request->user_id && $request->user_id > 0) {
                $data = $data->where('user_id', $request->user_id);
            }
            if ($request->approval_status && $request->approval_status != '') {
                $data = $data->where('approval_status', $request->approval_status);
            }

            //dd($data->get()->toArray());
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    $fullName = $row->user->first_name . ' ' . $row->user->last_name;
                    return $fullName;
                })
                ->addColumn('department', function ($row) {
                    $Department = Employee::where('user_id', $row->user->id)->with('department')->first();
                    return $Department->department->department_name;
                })
                ->addColumn('campus_office', function ($row) {
                    return $row->campus->type;
                })
                ->addColumn('branch_name', function ($row) {
                    return $row->branch->br_name;
                })
                ->addColumn('city_from', function ($row) {
                    return $row->fromCity->city_name;
                })
                ->addColumn('city_to', function ($row) {
                    return $row->toCity->city_name;
                })
                ->addColumn('travel_on', function ($row) {
                    return  Carbon::parse($row->travel_on)->format('d-m-Y');
                })
                ->addColumn('return_on', function ($row) {
                    return  Carbon::parse($row->return_on)->format('d-m-Y');
                })
                ->addColumn('approval_auth', function ($row) {
                    $fullName = $row->approvedBy->first_name . ' ' . $row->approvedBy->last_name;
                    return $fullName;
                })
                ->addColumn('action', function ($row) {
                    return view('Visitors.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $branches = Branch::all();
        $cities = City::all();
        $campus_types = CampusOfficeType::all();
        if (isSuperAdmin()) {
            $employees = Employee::where('branch_id', 2)->whereNull('left_date')->with('user', 'department')->orderBy('preferred_name')->get();
        } else {
            $employees = Employee::where('branch_id', 2)->where('reporting_to', auth()->user()->id)->whereNull('left_date')->with('user', 'department')->orderBy('preferred_name')->get();
        }
        return view('Visitors.index', compact(['branches','cities', 'campus_types','employees']));
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
        //dd($request->all());
        $request->validate([
            'user_id' => 'required',
            'campus_office_id' => 'required',
            'branch_id' => 'required',
            'from_city_id' => 'required',
            'to_city_id' => 'required',
            'total_duration' => 'required',
            'travel_on' => 'required',
            'return_on' => 'required',
            'travel_mode' => 'required',
            'purpose' => 'required',
        ]);
        $visit = VisitDetail::where('user_id', $request->user_id)->where('campus_office_id', $request->campus_office_id)->where('branch_id', $request->branch_id)->where('from_city_id', $request->from_city_id)->where('to_city_id', $request->to_city_id)->where('approval_status', 'pending')->get();
        if (isset($visit[0])) {
            return redirect()->route('visitDetail.index')
            ->with('error', 'Duplicate entries not allowed.');
        }
        $input = $request->all();
        $approval = Employee::where('user_id', $request->user_id)->first('reporting_to');
        $input['approved_by'] = $approval->reporting_to;

        //dd($input);

        visitDetail::create($input);

        if (isSuperAdmin()) {
            return redirect()->route('visitDetail.index')
                ->with('success', 'Visit has been created successfully.');
        } else {
            return redirect()->route('visitDetail.index', ['req' => $request->req])
            ->with('success', 'Visit has been created successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VisitDetail  $visitDetail
     * @return \Illuminate\Http\Response
     */
    public function show(VisitDetail $visitDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VisitDetail  $visitDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(VisitDetail $visitDetail)
    {
        $branches = Branch::all();
        $cities = City::all();
        $campus_types = CampusOfficeType::all();
        $employees = Employee::where('branch_id', 2)->whereNull('left_date')->with('user', 'department')->orderBy('preferred_name')->get();
        if (isSuperAdmin()) {
            return view('Visitors.index', compact(['branches','cities', 'campus_types','employees','visitDetail']));
        } else {
            //$req = 'my';
            return view('Visitors.index', [
                'req' => 'my',
                'branches' => $branches,
                'cities' => $cities,
                'campus_types' => $campus_types,
                'employees' => $employees,
                'visitDetail' => $visitDetail
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VisitDetail  $visitDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VisitDetail $visitDetail)
    {
        $request->validate([
            'user_id' => 'required',
            'campus_office_id' => 'required',
            'branch_id' => 'required',
            'from_city_id' => 'required',
            'to_city_id' => 'required',
            'total_duration' => 'required',
            'travel_on' => 'required',
            'return_on' => 'required',
            'travel_mode' => 'required',
            'purpose' => 'required',
        ]);
        $input = $request->all();
        $approval = Employee::where('user_id', $request->user_id)->first('reporting_to');
        $input['approved_by'] = $approval->reporting_to;

        //dd($input);

        $visitDetail->update($input);

        if (isSuperAdmin()) {
            return redirect()->route('visitDetail.index')->with('success', 'Visit has been updated successfully.');
        }


        return redirect()->route('visitDetail.index', ['req' => 'my'])->with('success', 'Visit has been updated successfully.');
    }

    public function edit_visit_status($rec_id)
    {
        if ($rec_id != null) {
            $id = $rec_id;
        }
        return view('Visitors.visit_status_modal', compact('id'));
    }

    public function update_visit_status(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'approval_status' => 'required',
        ]);
        $request_data = $request->all();

        $obj_visit = VisitDetail::find($request->id);
        $obj_visit->approval_status = $request_data['approval_status'];
        $obj_visit->save();
        if (isSuperAdmin()) {
            return redirect()->route('visitDetail.index')
            ->with('success', 'Visit status has been updated successfully.');
        } else {
            return redirect()->route('visitDetail.index', ['req' => 'me'])
                ->with('success', 'Visit status has been updated successfully.');
        }
            //return response()->json(['success' => "User password have been successfully updated!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VisitDetail  $visitDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(VisitDetail $visitDetail)
    {
        try {
            return $visitDetail->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
