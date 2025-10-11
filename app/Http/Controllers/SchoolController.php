<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\FeeStructureDetail;
use App\Models\State;
use App\Models\AcademicYear;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use App\Models\NewSchoolFeeStructure;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

// use DataTables;
// use Illuminate\Http\JsonResponse;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $states = State::all();
        $cities = City::all();
        // $fee_structure_records_popup = FeeStructureDetail::all();
        $fee_structure_records = NewSchoolFeeStructure::with([
            'new_fee_structure_details' => function ($query) {
                $query->whereIn('fee_status_by_dd', ['Approved', 'Pending', 'Rejected']);
            }
        ])->get();
        // $fee_structure_records_popup = NewSchoolFeeStructure::with(['fee_structure_details' => function($query) {
        //     $query->whereIn('fee_status_by_dd',[ 'Approved', 'Pending', 'Rejected']);
        // }])->get();
        // dd($fee_structure_records_popup);

        // dd($fee_structure_records);
        $academic_years = AcademicYear::all();

        return view('fee_structure.index', compact('fee_structure_records', 'academic_years', 'states', 'cities'));
    }
    public function filterFeeStructure(Request $request)
    {
        $stateId = $request->input('state_id');
        $cityId = $request->input('city_id');
        $academicYear = $request->input('academic_year_id');

        $query = NewSchoolFeeStructure::query();

        if ($stateId) {
            $query->whereHas('state', function ($q) use ($stateId) {
                $q->where('state_id', $stateId);
            });
        }
        if ($cityId) {
            $query->whereHas('city', function ($q) use ($cityId) {
                $q->where('city_id', $cityId);
            });
        }
        if ($academicYear) {
            $query->whereHas('academic_years', function ($q) use ($academicYear) {
                $q->where('academic_year_id', $academicYear);
            });
        }
        $savedRecords = $query->get();
        return view('fee_structure.partials.feestructure_table', compact('savedRecords'));
        /* $filteredData = $query->get();

        return response()->json($filteredData); */
    }
    //     public function filterAcademicYear(Request $request)
// {
//     $academicYearId = $request->input('academic_year_id');
//     // dd($academicYearId);
//     if ($academicYearId) {
//         $savedRecords = NewSchoolFeeStructure::where('academic_year_id', $academicYearId)->get();
//     } else {
//         $savedRecords = NewSchoolFeeStructure::all();
//     }

    //     // Return JSON response
//     return response()->json(['data' => $savedRecords]);
// }

    public function create()
    {
        // $id = 1;
        $savedRecords = FeeStructureDetail::with('new_school_fee_structure')->Where('fee_status_by_dd', 'Pending')->where('created_at', '>', Carbon::now()->subSeconds(10)->toDateTimeString())->get();
        // dd($savedRecords);
        $cities = City::all();
        $states = State::all();
        $academic_years = AcademicYear::all();
        $classGroups = ClassGroup::all();
        return view('fee_structure.create', compact('savedRecords', 'cities', 'states', 'academic_years', 'classGroups'));
    }




    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'state_id' => 'required',
            'city_id' => 'required',
            'academic_year_id' => 'required',
            'school_name' => 'required',
            'school_address' => 'required',
            'class_group_id' => 'required',
            'campus_area' => 'required',
            'date' => 'required',
            'nearest_bss_school' => 'required',
            'fee_charges' => 'required',
            'school_fee' => 'required',
            'admission_fee' => 'required',
            'security_fee' => 'required',
            'registration_fee' => 'required',
            'final_fee_charges' => 'required',
            'round_final_fee_charges' => 'required',
            'approval_date' => 'required',
            'remarks' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Loop through the dynamic form data and create records
        // dd($request->all());

        $new_school = NewSchoolFeeStructure::create([
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'academic_year_id' => $request->academic_year_id_new_school,
            'school_name' => $request->school_name,
            'school_address' => $request->school_address,
            'class_group_id' => $request->class_group_id,
            'campus_area' => $request->campus_area,
            'date' => $request->date,
            'remarks' => $request->remarks,

        ]);

        $new_school->new_fee_structure_details()->create([
            'nearest_bss_school' => $request->nearest_bss_school,
            'fee_charges' => $request->fee_charges,
            'school_fee' => $request->school_fee,
            'academic_year_id' => $request->academic_year_id,
            'admission_fee' => $request->admission_fee,
            'security_fee' => $request->security_fee,
            'registration_fee' => $request->registration_fee,
            'final_fee_charges' => $request->final_fee_charges,
            'round_final_fee_charges' => $request->round_final_fee_charges,
            'fee_status_by_dd' => 'Pending',
            'approval_date' => $request->approval_date,
            'remarks' => $request->fee_details_remarks,

        ]);


        return redirect()->back()->with('success', 'Fee Structure saved Successfully');
    }

    public function edit($id)
    {
        // dd($id);
        $fee_details = FeeStructureDetail::Where('id', $id)->first();
        // dd($fee_details);
        $new_school_fee_structure = $fee_details->new_school_fee_structure;
        // $record = NewSchoolFeeStructure::findOrFail($id);
        // dd($fee_details);
        $cities = City::all();
        $states = State::all();
        $academic_years = AcademicYear::all();
        $classGroups = ClassGroup::all();
        // dd($fee_details, $new_school_fee_structure);
        return view('fee_structure.edit', compact('fee_details', 'new_school_fee_structure', 'cities', 'states', 'academic_years', 'classGroups'));
    }

    public function index_edit($id)
    {
        $new_school_fee_structure = NewSchoolFeeStructure::Where('id', $id)->first();
        $cities = City::all();
        $states = State::all();
        $academic_years = AcademicYear::all();
        $classGroups = ClassGroup::all();

        return view('fee_structure.index_edit', compact('new_school_fee_structure', 'cities', 'states', 'academic_years', 'classGroups'));
    }

    // public function index_update(Request $request, $id)
    // {
    //     $record = FeeStructureDetail::findOrFail($id);

    //     $validator = Validator::make($request->all(), [
    //         'nearest_bss_school' => 'required',
    //         'fee_charges' => 'required|integer',
    //         'school_fee' => 'required|integer',
    //         'academic_year_id' => 'required',
    //         'admission_fee' => 'required|integer',
    //         'security_fee' => 'required|integer',
    //         'registration_fee' => 'required|integer',
    //         'final_fee_charges' => 'required',
    //         'round_final_fee_charges' => 'required|integer',
    //         'approval_date' => 'required|date',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->route('fee_structure.index_edit', $id)
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     $record->fee_structure_details()->create([
    //         'nearest_bss_school' => $request->nearest_bss_school,
    //         'fee_charges' => $request->fee_charges,
    //         'school_fee' => $request->school_fee,
    //         'academic_year_id' => $request->academic_year_id,
    //         'admission_fee' => $request->admission_fee,
    //         'security_fee' => $request->security_fee,
    //         'registration_fee' => $request->registration_fee,
    //         'final_fee_charges' => $request->final_fee_charges,
    //         'round_final_fee_charges' => $request->round_final_fee_charges,
    //         'fee_status_by_dd' => 'Pending',
    //         'approval_date' => $request->approval_date,
    //     ]);



    //     return redirect()->back()->with('success', 'Fee Structure updated successfully');
    // }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'nearest_bss_school' => 'required',
            'fee_charges' => 'required|integer',
            'school_fee' => 'required|integer',
            'academic_year_id' => 'required',
            'admission_fee' => 'required|integer',
            'security_fee' => 'required|integer',
            'registration_fee' => 'required|integer',
            'final_fee_charges' => 'required',
            'round_final_fee_charges' => 'required|integer',
            'approval_date' => 'required|date',
            'remarks' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('fee_structure.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }
        $parent = NewSchoolFeeStructure::find($request->parent_fee_structure);
        $parent->update(
            // Update based on this condition
            [
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'academic_year_id_new_school' => $request->academic_year_id_new_school,
                'school_name' => $request->school_name,
                'school_address' => $request->school_address,
                'class_group_id' => $request->class_group_id,
                'campus_area' => $request->campus_area,
                'date' => $request->date,
                'remarks' => $request->remarks,
            ]
        );
        // $record = FeeStructureDetail::find($request->id);
        FeeStructureDetail::updateOrCreate(
            ['id' => $request->id],

            // Update based on this condition
            [

                'new_school_fee_structure_id' => $id,

                'nearest_bss_school' => $request->nearest_bss_school,
                'fee_charges' => $request->fee_charges,
                'school_fee' => $request->school_fee,
                'academic_year_id' => $request->academic_year_id,
                'admission_fee' => $request->admission_fee,
                'security_fee' => $request->security_fee,
                'registration_fee' => $request->registration_fee,
                'final_fee_charges' => $request->final_fee_charges,
                'round_final_fee_charges' => $request->round_final_fee_charges,
                'fee_status_by_dd' => 'Pending',
                'approval_date' => $request->approval_date,
                'remarks' => $request->fee_details_remarks,
            ]


        );


        return redirect()->back()->with('success', 'Fee Structure updated successfully');
    }

    public function destroy($id)
    {
        $record = FeeStructureDetail::findOrFail($id);
        $record->delete();

        return redirect()->back()->with('success', 'Fee Structure deleted successfully');
    }

    public function destroyParent($id)
    {
        $record = NewSchoolFeeStructure::findOrFail($id);
        $record->delete();

        return redirect()->back()->with('success', 'Fee Structure deleted successfully');
    }

    public function changeStatus(Request $request)
{
    $record = FeeStructureDetail::find($request->id);
    $newStatus = $request->value;

    // Check if the status is changing to "Approved" or "Cancelled"
    if ($newStatus === 'Approved' || $newStatus === 'Cancelled') {
        // Update the status_approval_date to the current date and time
        $record->status_approval_date = now();
    } else {
        // If not "Approved" or "Cancelled," set status_approval_date to null
        $record->status_approval_date = null;
    }

    $record->fee_status_by_dd = $newStatus;
    $record->save();

    return response()->json(['success' => true, 'data' => $record, 'message' => 'Status updated']);
}


    // public function viewDetail(Request $request){
    //     $record = NewSchoolFeeStructure::find($request->id);
    //     $record->viewDetail = $request->id;
    //     $dd($re)
    // }

    public function complete_edit($id)
    {
        $child_data = FeeStructureDetail::where('id', $id)->first();
        // dd($child_data);
        //     // dd($child_data);
        return response()->json(['child_data' => $child_data]);
    }

    // public function getChildData($id)
    // {
    //     $child_data = FeeStructureDetail::where('id', $id)->first();
    //     // dd($child_data);
    //     return response()->json(['child_data' => $child_data]);
    // }

    // public function complete_update(Request $request, $id)
    // {
    //     $record = NewSchoolFeeStructure::findOrFail($id);

    //     $validator = Validator::make($request->all(), [
    //         'state_id' => 'required',
    //         'city_id' => 'required',
    //         'academic_year_id' => 'required',
    //         'school_name' => 'required',
    //         'school_address' => 'required',
    //         'class_group_id' => 'required',
    //         'campus_area' => 'required',
    //         'date' => 'required',
    //         'remarks' => 'required',
    //         'nearest_bss_school' => 'required',
    //         'fee_charges' => 'required|integer',
    //         'school_fee' => 'required|integer',
    //         'admission_fee' => 'required|integer',
    //         'security_fee' => 'required|integer',
    //         'registration_fee' => 'required|integer',
    //         'final_fee_charges' => 'required',
    //         'round_final_fee_charges' => 'required|integer',
    //         'approval_date' => 'required|date',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->route('fee_structure.edit', $id)
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     $record->fee_structure_details()->update([
    //         'nearest_bss_school' => $request->nearest_bss_school,
    //         'fee_charges' => $request->fee_charges,
    //         'school_fee' => $request->school_fee,
    //         'academic_year_id' => $request->academic_year_id,
    //         'admission_fee' => $request->admission_fee,
    //         'security_fee' => $request->security_fee,
    //         'registration_fee' => $request->registration_fee,
    //         'final_fee_charges' => $request->final_fee_charges,
    //         'round_final_fee_charges' => $request->round_final_fee_charges,
    //         'fee_status_by_dd' => 'Pending',
    //         'approval_date' => $request->approval_date,
    //     ]);

    //     return redirect()->route('fee_structure.index')->with('success', 'Fee Structure updated successfully');
    // }


}
