<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use App\Models\FranchiseApplication;
use Yajra\DataTables\Facades\DataTables;
use App\Models\FranchiseApplicationBdVisit;

class FranchiseApplicationBdVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FranchiseApplicationBdVisit::where('franchise_application_id', $request->franchise_application)->with(['visit_by.user', 'forward_to.user', 'approved_by.user'])->get();
             //dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('franchise_application.bd_visit_actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FranchiseApplication $franchise_application_id)
    {
        $bd_reps = Employee::with('user')->where('department_id','=','5')->get();
        $forward_to = Employee::with('user')->where('department_id','=','6')->get();
        //$nwa_detail = FranchiseApplication::find($franchise_application_id);
        if(isset($franchise_application_id->appl_name) && isset($franchise_application_id->appl_last_name))
        {
            $nwa_name = $franchise_application_id->appl_name.' '.$franchise_application_id->appl_last_name;
        }
        else{
            $nwa_name = $franchise_application_id->appl_name;
        }

        $nwa_contact = $franchise_application_id->contact_no_1;
        if(!empty($franchise_application_id->contact_no_2))
        {
            $nwa_contact = $nwa_contact.', '.$franchise_application_id->contact_no_2;
        }
        $class_groups = ClassGroup::all();
        return view('franchise_application.bd_visit', [
            'franchise_application' => $franchise_application_id,
            'bd_reps' => $bd_reps,
            'forward_to' => $forward_to,
            'nwa_name' => $nwa_name,
            'nwa_contact' => $nwa_contact,
            'class_groups' => $class_groups
        ]);
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
        $school_configuration = ClassGroup::where('id','=', $request->school_type)->first('description')->toArray();
        $request['school_configuration'] = $school_configuration['description'];
        //dd($request->all());

        $request->validate([
            "site_address" => "required",
            "school_type" => "required",
            "proposed_school_name" => "required",
            "visit_date" => "required",
            "bd_status" => "required",
            "site_purpose" => "required",
            "visit_by" => "required",
            "forward_to" => "required",
            "approved_by" => "required",
            "remarks" => "required",
            "school_configuration" => "required",
            // "area_population_half_km_radius" => "required",
            // "area_population_one_km_radius" => "required",
            // "area_population_two_km_radius" => "required",
            "franchise_application_id" => "required"
        ]);


        FranchiseApplicationBdVisit::create($request->all());
        return redirect()->back()->with(['success' => 'BD Visit added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FranchiseApplicationBdVisit  $franchiseApplicationBdVisit
     * @return \Illuminate\Http\Response
     */
    public function show(FranchiseApplicationBdVisit $franchiseApplicationBdVisit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FranchiseApplicationBdVisit  $franchiseApplicationBdVisits
     * @return \Illuminate\Http\Response
     */
    public function edit(FranchiseApplicationBdVisit $franchiseApplicationBd)
    {
        $bd_reps = Employee::with('user')->where('department_id','=','5')->get();
        $forward_to = Employee::with('user')->where('department_id','=','6')->get();
        $nwa_detail = FranchiseApplication::find($franchiseApplicationBd->franchise_application_id);
        //dd($nwa_detail['appl_name']);
        $nwa_name = $nwa_detail['appl_name'].' '.$nwa_detail['appl_last_name'];
        $nwa_contact = $nwa_detail['contact_no_1'];
        if(!empty($nwa_detail['contact_no_2']))
        {
            $nwa_contact = $nwa_contact.', '.$nwa_detail['contact_no_2'];
        }
        $class_groups = ClassGroup::all();
        return view('franchise_application.bd_visit', [
            'franchise_application' => ['id' => $franchiseApplicationBd->franchise_application_id],
            'franchise_application_bd_visit' => $franchiseApplicationBd,
            'bd_reps' => $bd_reps,
            'forward_to' => $forward_to,
            'nwa_name' => $nwa_name,
            'nwa_contact' => $nwa_contact,
            'class_groups' => $class_groups
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FranchiseApplicationBdVisit  $franchiseApplicationBdVisit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FranchiseApplicationBdVisit $franchiseApplicationBd)
    {
        $school_configuration = ClassGroup::where('id','=', $request->school_type)->first('name')->toArray();
        $request['school_configuration'] = $school_configuration['name'];
        //dd($request->all());
        if($franchiseApplicationBd->update($request->all()))
            return redirect()->back()->with(['success' => 'BD Visit updated successfully.']);

        return redirect()->back()->with(['error' => 'BD Visit not updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FranchiseApplicationBdVisit  $franchiseApplicationBdVisit
     * @return \Illuminate\Http\Response
     */
    public function destroy(FranchiseApplicationBdVisit $franchiseApplicationBdVisit)
    {
        //
    }
}
