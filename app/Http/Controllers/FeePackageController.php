<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\ClassFeePcakage;
use App\Models\ComClass;
use App\Models\Company;
use App\Models\FeePackage;
use App\Models\FeePackageType;
use App\Models\InvoiceFrequency;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;

class FeePackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FeePackage::with(['company', 'branch', 'academic_year', 'fee_package_type', 'from_class_id', 'to_class_id']);
            if($request->academic_year_id)
            {
                $data=$data->whereHas('academic_year',function($q)use($request){
                    $q->where('id',$request->academic_year_id);
                });
            }
            if($request->branch_id)
            {
                $data=$data->whereHas('branch',function($q) use ($request){
                    $q->where('id',$request->branch_id);
                });
            }
            if (($request->from_class_id))
            $data = $data->where(function ($q) use ($request){
                $q->where('com_class_id' , $request->from_class_id);
            });
            if (($request->to_class_id))
            $data = $data->where(function ($q) use ($request){
                $q->where('com_class_id' , $request->to_class_id);
            });
            if (($request->fee_package_type_id))
            $data = $data->where(function ($q) use ($request){
                $q->where('fee_package_type_id' , $request->fee_package_type_id);
            });
            // if($request->fee_package_type_id)
            // {
            //     $data=$data->whereHas('fee_package_type',function($q)use($request){
            //         $q->where('fee_package_type_id',$request->fee_package_type_id);
            //     });
            // }
            $data= $data->get();
            // dd($data->toArray());
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.fee_packages.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $academic_years = AcademicYear::all();
        $from_class_id = ComClass::all();
        $to_class_id = ComClass::all();
        $companies = Company::get();
        // $fee_package_type = FeePackage::all();
        $fee_package_type = FeePackageType::get();
    // dd($fee_package_type);
        $branches = Branch::get();
        return view('settings.fee_packages.fee_packages', ['companies' => $companies, 'branches' => $branches,'academic_years'=>$academic_years, 'from_class_id'=>$from_class_id, 'to_class_id'=> $to_class_id, 'fee_package_type' => $fee_package_type]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        $branches = Branch::all();
        $academic_years = AcademicYear::all();
        $fee_package_types = FeePackageType::all();
        $classes = ComClass::all();

        return view('settings.fee_packages.add_fee_package', [
            'companies' => $companies,
            'branches' => $branches,
            'academic_years' => $academic_years,
            'fee_package_types' => $fee_package_types,
            'classes' => $classes
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
        $from_class = ComClass::where('id', $request->from_class_id)->first();
        $to_class = ComClass::where('id', $request->to_class_id)->first();

        $classes = ComClass::whereBetween('sort', [$from_class->sort, $to_class->sort])->get();
        // dd($classes);

        $request->validate([
            'company_id' => 'required',
            'branch_id' => 'required',
            'academic_year_id' => 'required',
            'package_name' => 'required|unique:fee_packages,package_name',
        ]);
        // dd($request->all());
        $fee_package = FeePackage::create($request->all());
        $fee_package->classes()->attach($classes);

        return redirect()->route('fee-packages.index')
            ->with('success', 'Fee Package created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FeePackage  $feePackage
     * @return \Illuminate\Http\Response
     */
    public function show(FeePackage $feePackage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeePackage  $feePackage
     * @return \Illuminate\Http\Response
     */
    public function edit(FeePackage $feePackage)
    {
        $companies = Company::all();
        $branches = Branch::all();
        $academic_years = AcademicYear::all();
        $fee_package_types = FeePackageType::all();
        $classes = ComClass::all();

        return view('settings.fee_packages.edit_fee_package', [
            'companies' => $companies,
            'branches' => $branches,
            'academic_years' => $academic_years,
            'fee_package_types' => $fee_package_types,
            'feePackage' => $feePackage,
            'classes' => $classes
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeePackage  $feePackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeePackage $feePackage)
    {
        $request->validate([
            'company_id' => 'required',
            'branch_id' => 'required',
            'package_name' => 'required|unique:fee_packages,package_name,' . $feePackage->id,
            'from_class_id' => 'required',
            'to_class_id' => 'required'
        ]);
        $from_class = ComClass::where('id', $request->from_class_id)->first();
        $to_class = ComClass::where('id', $request->to_class_id)->first();

        $classes = ComClass::whereBetween('sort', [$from_class->sort, $to_class->sort])->get();

        $feePackage->update($request->all());
        $feePackage->classes()->sync($classes);

        return redirect()->route('fee-packages.index')
            ->with('success', 'Fee Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeePackage  $feePackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeePackage $feePackage)
    {
        try {
            return $feePackage->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
