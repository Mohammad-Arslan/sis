<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Designation;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\DesignationType;
use Illuminate\Database\QueryException;
use DataTables;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Designation::with(['company','designation_types'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('company_name', function ($row) {
                    return isset($row['company']) ? $row['company']['company_name'] : '-';
                })
                ->addColumn('role_name', function ($row) {
                    return isset($row['role']) ? $row['role']['display_name'] : '-';
                })
                ->addColumn('action', function ($row) {
                    return view('settings.designations.actions', ['row' => $row]);
                })
                ->addColumn('for_school', function ($row) {
                    return $row->for_school ? 'Yes' : 'No';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $data['companies'] = Company::get();
        $data['designation_types'] = DesignationType::all();
        $data['roles'] = Role::all();
        return view('settings.designations.designations', $data);
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
            'designation_name' => 'required',
            //'company_id' => 'required',
            'role_id' => 'required',
            'type_id' => 'required'
        ]);

        Designation::create($request->all());

        return redirect()->route('designations.index')
            ->with('success', 'Designation created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function edit(Designation $designation)
    {
        $data['companies'] = Company::get();
        $data['designation_types'] = DesignationType::all();
        $data['roles'] = Role::all();
        $data['designation'] = $designation;

        return view('settings.designations.designations', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designation $designation)
    {
        $request->validate([
            'designation_name' => 'required|unique:designations,designation_name,' . $designation->id,
            //'company_id' => 'required',
            'type_id' => 'required',
            'role_id' => 'required',
        ]);

        $designation->update($request->all());

        return redirect()->route('designations.index')
            ->with('success', 'Designation has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Designation $designation)
    {
        try {
            return $designation->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
