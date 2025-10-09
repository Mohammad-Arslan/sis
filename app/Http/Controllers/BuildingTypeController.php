<?php

namespace App\Http\Controllers;

use App\Models\BuildingType;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class BuildingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BuildingType::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.building_type.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        //dd($parents->toArray());
        return view('settings.building_type.building_types');
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

        BuildingType::create($request->all());

        return redirect()->route('building-type.index')
            ->with('success', 'Fee Period created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BuildingType  $buildingType
     * @return \Illuminate\Http\Response
     */
    public function show(BuildingType $buildingType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BuildingType  $buildingType
     * @return \Illuminate\Http\Response
     */
    public function edit(BuildingType $buildingType)
    {
        return view('settings.building_type.building_types', ['buildingType' => $buildingType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BuildingType  $buildingType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BuildingType $buildingType)
    {
        $request->validate([
            'type_name' => 'required',
            // 'type_description' => 'required',
        ]);

        $buildingType->update($request->all());

        return redirect()->route('building-type.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BuildingType  $buildingType
     * @return \Illuminate\Http\Response
     */
    public function destroy(BuildingType $buildingType)
    {
        try {
            return $buildingType->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
