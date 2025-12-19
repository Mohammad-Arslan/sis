<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\FranchiseApplication;
// use Barryvdh\DomPDF\PDF;
use App\Models\FranchiseApplicationQa;
use Yajra\DataTables\Facades\DataTables;
use App\Models\FrachiseApplicationRemark;
use App\Models\FranchiseApplicationBdVisit;

class FranchiseApplicationQaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->franchise_application);
        if ($request->ajax()) {
            $data = FranchiseApplicationQa::where('franchise_application_id', $request->franchise_application)->with(['sales_rep.user', 'qa_rep.user', 'regional_head.user'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('franchise_application.qa_visit_report_action', ['row' => $row]);
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
        // $sales_reps = Employee::with(['designation'])->whereHas('designation', function ($query) {
        //     $query->where(['designation_name' => 'Assistant Manager New Media']);
        // })->get();
        $sales_reps = Employee::with('user')->where('department_id', '=', '5')->get();
        $qa_reps = Employee::with('user')->where('department_id', '=', '6')->get();
        $regional_heads = Employee::with('user')->where('department_id', '=', '5')->get();
        $class_groups = ClassGroup::all();
        //dd($franchise_application_id->id);
        $forwardedDate = FranchiseApplicationBdVisit::where('franchise_application_id', $franchise_application_id->id)->latest('id')->first('forwarded_date');
        if ($forwardedDate) {
            $forwarded_date = Carbon::parse($forwardedDate->forwarded_date)->format('d-m-Y');
        } else {
            $forwarded_date = null;
        }
        return view('franchise_application.franchise_application_qa', [
            'sales_reps' => $sales_reps,
            'qa_reps' => $qa_reps,
            'class_groups' => $class_groups,
            'franchise_application' => $franchise_application_id,
            'regional_heads' => $regional_heads,
            'forwarded_date' => $forwarded_date
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
        // dd($request->all());
        $request->validate([
            'proposed_location' => 'required',
            'visit_date' => 'required',
            'sales_rep_id' => 'required',
            'qa_rep_id' => 'required',
            'client_name' => 'required',
            //'client_contact' => 'required',
            'visit_purpose' => 'required',
            'school_type' => 'required',
            'qa_remarks' => 'required',
            'plot_size_required' => 'required|max:15',
            'required_uom' => 'required',
            'plot_size_actual' => 'required|max:15',
            'actual_uom' => 'required',
            'site_location' => 'required',
            'type_of_building' => 'required',
            'type_of_construction' => 'required',
            'building_covered_area_required' => 'required|max:15',
            'building_covered_area_actual' => 'required|max:15',
            'total_open_area_01' => 'required|max:15',
            'total_open_area_02' => 'required|max:15',
            'no_of_classrooms_required' => 'required|max:15',
            'no_of_classrooms_actual' => 'required|max:15',
            'total_lab_room_library_required' => 'required|max:15',
            'total_lab_room_library_actual' => 'required|max:15',
            'classroom_size_required' => 'required|max:15',
            'classroom_size_actual' => 'required|max:15',
            'provision_of_extension' => 'required',
            'admin_block' => 'required',
            //'area_population_radius_required' => 'required|max:15',
            //'area_population_radius_actual' => 'required|max:15',
            'school_in_vicinity_01' => 'required|max:15',
            'school_in_vicinity_02' => 'required|max:15',
            'type_of_locality' => 'required',
            'is_centrally_located' => 'required',
            'is_market_nearby' => 'required',
            'road_access' => 'required',
            'road_access_type_01' => 'required|max:15',
            'road_access_type_02' => 'required|max:15',
            'facing_road_width' => 'required|max:15',
            'facing_road_size' => 'required|max:15',
            'is_public_transport_nearby' => 'required',
            'noise_pollution' => 'required',
            'is_hospital_nearby' => 'required',
            'is_rescue_nearby' => 'required',
            'is_police_nearby' => 'required',
            'is_river_nearby' => 'required',
            'is_flood_protected' => 'required',
            'seperate_visitor_parking' => 'required',
            'traffic_signs' => 'required',
            'dead_end_street' => 'required',
            'playground_available' => 'required',
            'minimum_playground_35' => 'required',
            'boundary_wall_plaster' => 'required',
            'building_plaster' => 'required',
            'building_paintwork' => 'required',
            'window_door_condition' => 'required',
            'window_door_paintwork' => 'required',
            'roof_condition' => 'required',
            'wall_condition' => 'required',
            'floor_condition' => 'required',
            'slipping_hazard' => 'required',
            'electrical_observation' => 'required',
            'boundary_wall_height' => 'required',
            'main_along_wicked_gate' => 'required',
            'guardroom' => 'required',
            'seperate_toilet_visitor' => 'required',
            'toilet_away_from_class' => 'required',
            'student_washroom' => 'required',
            'classroom_lighting' => 'required',
            'overall_lighting' => 'required',
            'classroom_ventilation' => 'required',
            'science_lab_lighting' => 'required',
            'science_lab_ventilation' => 'required',
            'toilet_ventilation' => 'required',
            'overall_ventilation' => 'required',
            'guardroom_inside_gate' => 'required',
            'distance_to_ucs_campus_required' => 'required|max:15',
            'distance_to_ucs_campus_actual' => 'required|max:15',
            // 'regional_head_id' => 'required',
            // 'regional_head_sec_date' => 'required',
            // 'head_status' => 'required',
            // 'regional_head_sec_remarks' => 'required'
        ]);

        FranchiseApplicationQa::create($request->all());
        return redirect()->back()->with('success', 'QA visit report created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FranchiseApplicationQa  $franchiseApplicationQa
     * @return \Illuminate\Http\Response
     */
    public function show(FranchiseApplicationQa $franchiseApplicationQa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FranchiseApplicationQa  $franchiseApplicationQa
     * @return \Illuminate\Http\Response
     */
    public function edit(FranchiseApplicationQa $franchiseApplicationQa)
    {
        $sales_reps = Employee::with('user')->where('department_id', '=', '5')->get();
        $qa_reps = Employee::with('user')->where('department_id', '=', '6')->get();
        $regional_heads = Employee::with('user')->where('department_id', '=', '5')->get();
        $class_groups = ClassGroup::all();
        return view('franchise_application.franchise_application_qa', [
            'sales_reps' => $sales_reps,
            'qa_reps' => $qa_reps,
            'class_groups' => $class_groups,
            'regional_heads' => $regional_heads,
            'franchise_application_qa' => $franchiseApplicationQa,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FranchiseApplicationQa  $franchiseApplicationQa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FranchiseApplicationQa $franchiseApplicationQa)
    {
        $request->validate([
            'proposed_location' => 'required',
            'visit_date' => 'required',
            'sales_rep_id' => 'required',
            'qa_rep_id' => 'required',
            'client_name' => 'required',
            //'client_contact' => 'required',
            'visit_purpose' => 'required',
            'school_type' => 'required',
            'qa_remarks' => 'required',
            'plot_size_required' => 'required|max:15',
            'required_uom' => 'required',
            'plot_size_actual' => 'required|max:15',
            'actual_uom' => 'required',
            'site_location' => 'required',
            'type_of_building' => 'required',
            'type_of_construction' => 'required',
            'building_covered_area_required' => 'required|max:15',
            'building_covered_area_actual' => 'required|max:15',
            'total_open_area_01' => 'required|max:15',
            'total_open_area_02' => 'required|max:15',
            'no_of_classrooms_required' => 'required|max:15',
            'no_of_classrooms_actual' => 'required|max:15',
            'total_lab_room_library_required' => 'required|max:15',
            'total_lab_room_library_actual' => 'required|max:15',
            'classroom_size_required' => 'required|max:15',
            'classroom_size_actual' => 'required|max:15',
            'provision_of_extension' => 'required',
            'admin_block' => 'required',
            //'area_population_radius_required' => 'required|max:15',
            //'area_population_radius_actual' => 'required|max:15',
            'school_in_vicinity_01' => 'required|max:15',
            'school_in_vicinity_02' => 'required|max:15',
            'type_of_locality' => 'required',
            'is_centrally_located' => 'required',
            'is_market_nearby' => 'required',
            'road_access' => 'required',
            'road_access_type_01' => 'required|max:15',
            'road_access_type_02' => 'required|max:15',
            'facing_road_width' => 'required|max:15',
            'facing_road_size' => 'required|max:15',
            'is_public_transport_nearby' => 'required',
            'noise_pollution' => 'required',
            'is_hospital_nearby' => 'required',
            'is_rescue_nearby' => 'required',
            'is_police_nearby' => 'required',
            'is_river_nearby' => 'required',
            'is_flood_protected' => 'required',
            'seperate_visitor_parking' => 'required',
            'traffic_signs' => 'required',
            'dead_end_street' => 'required',
            'playground_available' => 'required',
            'minimum_playground_35' => 'required',
            'boundary_wall_plaster' => 'required',
            'building_plaster' => 'required',
            'building_paintwork' => 'required',
            'window_door_condition' => 'required',
            'window_door_paintwork' => 'required',
            'roof_condition' => 'required',
            'wall_condition' => 'required',
            'floor_condition' => 'required',
            'slipping_hazard' => 'required',
            'electrical_observation' => 'required',
            'boundary_wall_height' => 'required',
            'main_along_wicked_gate' => 'required',
            'guardroom' => 'required',
            'seperate_toilet_visitor' => 'required',
            'toilet_away_from_class' => 'required',
            'student_washroom' => 'required',
            'classroom_lighting' => 'required',
            'overall_lighting' => 'required',
            'classroom_ventilation' => 'required',
            'science_lab_lighting' => 'required',
            'science_lab_ventilation' => 'required',
            'toilet_ventilation' => 'required',
            'overall_ventilation' => 'required',
            'guardroom_inside_gate' => 'required',
            'distance_to_ucs_campus_required' => 'required|max:15',
            'distance_to_ucs_campus_actual' => 'required|max:15',
            //'regional_head_id' => 'required',
            //'regional_head_sec_date' => 'required',
            //'head_status' => 'required',
            //'regional_head_sec_remarks' => 'required'
        ]);
        $franchiseApplicationQa->update($request->all());
        return redirect()->back()->with('success', 'QA visit report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FranchiseApplicationQa  $franchiseApplicationQa
     * @return \Illuminate\Http\Response
     */
    public function destroy(FranchiseApplicationQa $franchiseApplicationQa)
    {
        //
    }

    public function list($franchise_application_id)
    {
        return view('franchise_application.qa_visit_report_list', ['franchise_application' => ['id' => $franchise_application_id]]);
    }

    public function view_pdf($franchise_application_qa_id)
    {
        $data['franchise_application_qa'] = FranchiseApplicationQa::with([
            'qa_rep.user', 'sales_rep.user', 'regional_head.user','class_group'
        ])->where('id', $franchise_application_qa_id)->first();
        // dd($data['franchise_application_qa'] ->toArray());
        $data['franchise_application_qa']['BD_Remarks'] = FrachiseApplicationRemark::where('franchise_application_id', $data['franchise_application_qa']['franchise_application_id'])->where('observation_for', 'QA_Report')->where('user_role', 'manager-business-development')->orWhere('user_role', 'senior-manager-business-development')->latest('id')->first('observation');
        $data['franchise_application_qa']['DD_Remarks'] = FrachiseApplicationRemark::where('franchise_application_id', $data['franchise_application_qa']['franchise_application_id'])->where('observation_for', 'QA_Report')->where('user_role', 'deputy-director')->latest('id')->first('observation');
        return view('franchise_application.franchise_application_qa_report', $data);
        // dd($data['franchise_application_qa']->toArray());
    }

    public function create_pdf($franchise_application_qa_id)
    {
        $data['franchise_application_qa'] = FranchiseApplicationQa::with([
            'qa_rep.user', 'sales_rep.user', 'regional_head.user','class_group'
        ])->where('id', $franchise_application_qa_id)->first();
        // dd($data['franchise_application_qa'] ->toArray());
        $data['franchise_application_qa']['BD_Remarks'] = FrachiseApplicationRemark::where('franchise_application_id', $data['franchise_application_qa']['franchise_application_id'])->where('observation_for', 'QA_Report')->where('user_role', 'manager-business-development')->orWhere('user_role', 'senior-manager-business-development')->latest('id')->first('observation');
        $data['franchise_application_qa']['DD_Remarks'] = FrachiseApplicationRemark::where('franchise_application_id', $data['franchise_application_qa']['franchise_application_id'])->where('observation_for', 'QA_Report')->where('user_role', 'deputy-director')->latest('id')->first('observation');
        $pdf = Pdf::loadView('franchise_application.franchise_application_qa_pdf', $data);
        // dd($data['franchise_application_qa']->toArray());
        $file_name = $data['franchise_application_qa']['client_name'] . ' ' . 'franchise_application_qa.pdf';
        return $pdf->download(
            $file_name
        );
    }
}
