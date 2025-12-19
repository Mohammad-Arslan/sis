<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Section;
use App\Models\ComClass;
use App\Models\BranchClass;
use Illuminate\Support\Str;
use App\Models\AcademicYear;
use App\Models\BeamsChallan;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\BranchClassSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class BeamsChallanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole('super_admin|finance-manager')) {
                $data = BeamsChallan::with(['branch','academic_year','branch_class_id','class_section_id']);
            } else {
                //for network_associate
                $branch_id = get_branch_id();
                $data = BeamsChallan::where('branch_id', '=', $branch_id)->with(['branch','academic_year','branch_class_id','class_section_id']);
            }



            if ($request->filter_branch_id && $request->filter_branch_id > 0) {
                $data = $data->where('branch_id', $request->filter_branch_id);
            }

            if ($request->filter_academic_year_id && $request->filter_academic_year_id > 0) {
                $data = $data->where('academic_year_id', $request->filter_academic_year_id);
            } else {
                $data = $data->whereHas('academic_year', function ($query) use ($request) {
                    $query->where('active', 1);
                });
            }

            if ($request->filter_class_id && $request->filter_class_id > 0) {
                $data = $data->where('class_id', $request->filter_class_id);
            }

            if ($request->filter_section_id && $request->filter_section_id > 0) {
                $data = $data->where('section_id', $request->filter_section_id);
            }

            if ($request->filter_months && $request->filter_months > 0) {
                $data = $data->where('challan_month', $request->filter_months);
            }

            if ($request->searchName && $request->searchName != null) {
                $data = $data->whereHas('branch', function ($query) use ($request) {
                    $query->where('br_name', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('branch_code', 'like', '%' . $request->searchName . '%');

                    // $query->orWhereHas('academic_year', function ($query) use ($request) {
                    //     $query->where('title', 'like', '%' . $request->searchName . '%');
                    // });
                    // $query->orWhereHas('branch_class_id', function ($query) use ($request) {
                    //       $query->where('class_name', 'like', '%' . $request->searchName . '%');
                    // });
                    // $query->orWhereHas('class_section_id', function ($query) use ($request) {
                    //     $query->where('section_name', 'like', '%' . $request->searchName . '%');
                    // });
                });
            }
            //dd($data->get()->toArray());
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return $row['branch'] ? $row['branch']['br_name'] : '';
                })
                ->addColumn('academic_year', function ($row) {
                    return $row['academic_year'] ? $row['academic_year']['title'] : '';
                })
                ->addColumn('class_name', function ($row) {
                    return $row['branch_class_id'] ? $row['branch_class_id']['class_name'] : '';
                })

                ->addColumn('section_name', function ($row) {
                    return $row['class_section_id'] ? $row['class_section_id']['section_name'] : '';
                })
                ->addColumn('challan_month', function ($row) {
                    return $row['challan_month'] ? $row['challan_month'] : '';
                })
                ->addColumn('challan_pdf', function ($row) {
                    return view('beams_challans.challan_pdf_td', ['row' => $row]);
                })
                ->addColumn('action', function ($row) {
                    return view('beams_challans.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $academic_years = AcademicYear::all();
        $branches = Branch::all();
        if (Auth::user()->hasRole('super_admin|finance-manager')) {
            $classes = ComClass::all();
            $sections = Section::all();
        } else {
            $branch_id = get_branch_id();
            $branch_classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes'])->get();
            for ($i = 0; $i < count($branch_classes); $i++) {
                $classes[$i]['id'] = $branch_classes[$i]['com_classes']['id'];
                $classes[$i]['class_name'] = $branch_classes[$i]['com_classes']['class_name'];
            }
             $sections = Section::all();
            //$sections = BranchClassSection::where('branch_id' , $branch_id)->with(['sections'])->get();
        }
        return view('beams_challans.beams_challans', [
            'academic_years'    => $academic_years,
            'branches'          => $branches,
            'classes'           => $classes,
            'sections'          => $sections,
        ]);
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
            'academic_year_id' => 'required',
            'branch_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'challan_month' => 'required',
            'challan_pdf' => 'required|mimes:pdf|max:10000',
        ]);

        $input = $request->all();
        if ($request->hasfile('challan_pdf')) {
            $path = public_path() . '/uploads/beamschallans/';
            if (! File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $destination_path = public_path('/uploads/beamschallans');
            $challan_pdf_filename = Str::random(32) . '.' . $request->challan_pdf->getClientOriginalExtension();
            $request->challan_pdf->move($destination_path, $challan_pdf_filename);
            $input['challan_pdf'] = $challan_pdf_filename;

            $beams_challan = BeamsChallan::create($input);
            \DB::commit();

            return redirect(route('beams-challans.index'))->with('success', 'Beams challan has been successfully saved.');
        } else {
            return redirect(route('beams-challans.index'))->with('error', 'Beams challan not uploaded, PDF attachment is missing.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BeamsChallan  $beamsChallan
     * @return \Illuminate\Http\Response
     */
    public function show(BeamsChallan $beamsChallan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BeamsChallan  $beamsChallan
     * @return \Illuminate\Http\Response
     */
    public function edit(BeamsChallan $beamsChallan)
    {
        $academic_years = AcademicYear::all();
        $branches = Branch::all();
        $classes = ComClass::all();
        $sections = Section::all();
        return view('beams_challans.beams_challans', [
            'academic_years'    => $academic_years,
            'branches'          => $branches,
            'classes'           => $classes,
            'sections'          => $sections,
            'beamsChallan'      => $beamsChallan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BeamsChallan  $beamsChallan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BeamsChallan $beamsChallan)
    {
        $request->validate([
            'academic_year_id' => 'required',
            'branch_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'challan_month' => 'required',
            'challan_pdf' => 'mimes:pdf|max:10000',
        ]);
        $beam_challan_record = BeamsChallan::find($beamsChallan->id);
        $input = $request->all();
        if ($request->hasfile('challan_pdf')) {
            $path = public_path() . '/uploads/beamschallans/';
            if (! File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $destination_path = public_path('/uploads/beamschallans');
            $challan_pdf_filename = Str::random(32) . '.' . $request->challan_pdf->getClientOriginalExtension();
            $request->challan_pdf->move($destination_path, $challan_pdf_filename);
            $input['challan_pdf'] = $challan_pdf_filename;
            if (File::exists($path . $beam_challan_record['challan_pdf'])) {
                File::delete($path . $beam_challan_record['challan_pdf']);
            }
        } else {
           //
        }
        if ($beam_challan_record->update($input)) {
            return redirect(route('beams-challans.index'))->with('success', 'Beams challan has been successfully updated.');
        } else {
            return redirect(route('beams-challans.index'))->with('error', 'Beams challan not updated, Please try again later!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BeamsChallan  $beamsChallan
     * @return \Illuminate\Http\Response
     */
    public function destroy(BeamsChallan $beamsChallan)
    {
        try {
            return $beamsChallan->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
