<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\branch_security;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;

class BranchSecurityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = branch_security::with(['academic_year', 'branch', 'user']);
            //dd($data->get()->toArray());
            if ($request->academic_year_id && $request->academic_year_id > 0) {
                $data = $data->whereHas('academic_year', function ($query) use ($request) {
                    $query->where('id', $request->academic_year_id);
                });
            }

            if ($request->branch_id && $request->branch_id > 0) {
                $data = $data->whereHas('branch', function ($query) use ($request) {
                    $query->where('id', $request->branch_id);
                });
            }

            if ($request->searchName && $request->searchName != null) {
                $data = $data->where(function ($query) use ($request) {
                    $query->orWhere('amount', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('created_at', 'like', '%' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('branch', function ($query) use ($request) {
                    $query->where('br_name', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('branch_code', 'like', '%' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('first_name', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('last_name', 'like', '%' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('academic_year', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->searchName . '%');
                });
            }
            //dd($data->get()->toArray());
            $data = $data->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_code', function ($row) {
                    return  isset($row['branch']) ? $row['branch']['branch_code'] : '';
                })
                ->addColumn('branch_name', function ($row) {
                    return  isset($row['branch']) ? $row['branch']['br_name'] : '';
                })
                ->addColumn('user', function ($row) {
                    return  isset($row['user']) ? $row['user']['name'] : '';
                })
                ->addColumn('action', function ($row) {
                    return view('settings.branch_security.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $academic_years = AcademicYear::all();
        $branches = Branch::get();

        return view('settings.branch_security.branch_security', ['academic_years' => $academic_years, 'branches' => $branches]);
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
            'branch_id' => 'required',
            'academic_year_id' => 'required',
            'amount' => 'required|numeric',
            'created_by' => 'required',
        ]);

        $pre_exist = null;
        $pre_exist = branch_security::where('branch_id', $request->branch_id)->where('academic_year_id', $request->academic_year_id)->first();
        if (isset($pre_exist->branch_id) && isset($pre_exist->academic_year_id)) {
            return redirect()->route('branch-securities.index')
            ->with('error', 'Branch security already exist for selected academic year! Duplicates not allowed.');
        }


        branch_security::create($request->all());

        return redirect()->route('branch-securities.index')
            ->with('success', 'Branch security charges created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\branch_security  $branch_security
     * @return \Illuminate\Http\Response
     */
    public function show(branch_security $branch_security)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\branch_security  $branch_security
     * @return \Illuminate\Http\Response
     */
    public function edit(branch_security $branch_security)
    {
        $branches = Branch::get();
        $academic_years = AcademicYear::all();
        return view('settings.branch_security.branch_security', [
            'branch_security' => $branch_security,
            'branches' => $branches,
            'academic_years' => $academic_years
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\branch_security  $branch_security
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, branch_security $branch_security)
    {
        $request->validate([
            'branch_id' => 'required',
            'academic_year_id' => 'required',
            'amount' => 'required|numeric',
            'created_by' => 'required',
        ]);

        $branch_security->update($request->all());

        return redirect()->route('branch-securities.index')
            ->with('success', 'Branch security charges updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\branch_security  $branch_security
     * @return \Illuminate\Http\Response
     */
    public function destroy(branch_security $branch_security)
    {
        try {
            return $branch_security->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
