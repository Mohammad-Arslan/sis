<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\Company;
use App\Models\FeeConcession;
use App\Models\FeeConcessionType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FeeConcessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FeeConcession::with(['fee_concession_type', 'company', 'branch', 'academic_year']);
            //dd($data->toArray());
            if ($request->company_id && $request->company_id > 0) {
                $data = $data->whereHas('company', function ($query) use ($request) {
                    $query->where('id', $request->company_id);
                });
            }

            if ($request->branch_id && $request->branch_id > 0) {
                $data = $data->whereHas('branch', function ($query) use ($request) {
                    $query->where('id', $request->branch_id);
                });
            }

            if ($request->concession_id && $request->concession_id > 0) {
                $data = $data->whereHas('fee_concession_type', function ($query) use ($request) {
                    $query->where('id', $request->concession_id);
                });
            }

            if ($request->searchName && $request->searchName != null) {

                $data = $data->where(function($query) use ($request){
                    $query->orWhere('concession_percentage', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('created_at', 'like', '%' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('company', function ($query) use ($request) {
                    $query->where('company_name', 'like', '%' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('branch', function ($query) use ($request) {
                    $query->where('br_name', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('branch_code', 'like', '%' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('academic_year', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('fee_concession_type', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->searchName . '%');
                });
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.fee_concessions.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $fee_concessions_type = FeeConcessionType::get();
        $companies = Company::get();
        $branches = Branch::get();

        return view('settings.fee_concessions.fee_concessions', ['fee_concessions_type' => $fee_concessions_type, 'companies' => $companies, 'branches' => $branches]);
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
            'company_id' => 'required',
            'branch_id' => 'required',
            'fee_concession_type_id' => 'required',
            'academic_year_id' => 'required',
            'concession_percentage' => 'required|numeric',
        ]);

        FeeConcession::create($request->all());

        return redirect()->route('fee-concessions.index')
            ->with('success', 'Fee Concessions created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FeeConcession  $feeConcession
     * @return \Illuminate\Http\Response
     */
    public function show(FeeConcession $feeConcession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeeConcession  $feeConcession
     * @return \Illuminate\Http\Response
     */
    public function edit(FeeConcession $feeConcession)
    {
        $fee_concessions_type = FeeConcessionType::get();
        $companies = Company::get();
        $branches = Branch::get();
        $academic_years = AcademicYear::all();
        return view('settings.fee_concessions.fee_concessions', [
            'fee_concessions_type' => $fee_concessions_type,
            'feeConcession' => $feeConcession,
            'companies' => $companies,
            'branches' => $branches,
            'academic_years' => $academic_years
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeeConcession  $feeConcession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeeConcession $feeConcession)
    {
        $request->validate([
            'company_id' => 'required',
            'branch_id' => 'required',
            'fee_concession_type_id' => 'required',
            'academic_year_id' => 'required',
            'concession_percentage' => 'required',
        ]);

        $feeConcession->update($request->all());

        return redirect()->route('fee-concessions.index')
            ->with('success', 'Fee Concessions updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeeConcession  $feeConcession
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeeConcession $feeConcession)
    {
        try {
            return $feeConcession->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
