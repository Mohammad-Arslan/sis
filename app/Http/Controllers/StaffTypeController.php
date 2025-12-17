<?php

namespace App\Http\Controllers;

use App\Models\StaffType;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class StaffTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StaffType::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.staff_type.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        //dd($parents->toArray());
        return view('settings.staff_type.staff_types');
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
            'type_name' => 'required',
            // 'type_description' => 'required',
        ]);

        StaffType::create($request->all());

        return redirect()->route('staff-type.index')
            ->with('success', 'Staff type has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function show(StaffType $staffType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffType $staffType)
    {
        return view('settings.staff_type.staff_types', ['staffType' => $staffType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaffType $staffType)
    {
        $request->validate([
            'type_name' => 'required',
            // 'description' => 'required',
        ]);

        $staffType->update($request->all());

        return redirect()->route('staff-type.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StaffType  $staffType
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffType $staffType)
    {
        try {
            return $staffType->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
