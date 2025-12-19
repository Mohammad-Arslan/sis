<?php

namespace App\Http\Controllers;

use App\Models\ApplicationType;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class ApplicationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ApplicationType::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.application_type.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        //dd($parents->toArray());
        return view('settings.application_type.application_types');
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
            'name' => 'required',
            'description' => 'required',
        ]);

        ApplicationType::create($request->all());

        return redirect()->route('application-type.index')
            ->with('success', 'Leave application type created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationType $applicationType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplicationType $applicationType)
    {
        return view('settings.application_type.application_types', ['applicationType' => $applicationType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApplicationType $applicationType)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $applicationType->update($request->all());

        return redirect()->route('application-type.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApplicationType $applicationType)
    {
        try {
            return $applicationType->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
