<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.companies.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.companies.companies');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.companies.add_company');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|unique:companies,company_name',
        ]);

        $company = Company::create($request->all());

        return redirect(route('companies.edit', $company->id) . '?tab=basic')
            ->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Company $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $data = [
            'company' => $company
        ];
        return view('settings.companies.add_company', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'company_name' => 'required|unique:companies,company_name,' . $company->id,
        ]);

        $company->update($request->all());

        return redirect(route('companies.edit', $company->id) . '?tab=basic')
            ->with('success', 'Company created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Company $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        try {
            return $company->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
