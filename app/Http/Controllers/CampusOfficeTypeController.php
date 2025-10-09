<?php

namespace App\Http\Controllers;

use App\Models\CampusOfficeType;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DataTables;
class CampusOfficeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = CampusOfficeType::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.campus_office_types.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        return view('settings.campus_office_types.campus_types');
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
            'type' => 'required',
        ]);
        $type = CampusOfficeType::where('type',$request->type)->get();
        if(isset($type[0]))
        {
            return redirect()->route('campusOfficeType.index')
            ->with('error', 'Duplicate entries not allowed.');
        }

        campusOfficeType::create($request->all());

        return redirect()->route('campusOfficeType.index')
            ->with('success', 'Campus/Office type created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CampusOfficeType  $campusOfficeType
     * @return \Illuminate\Http\Response
     */
    public function show(CampusOfficeType $campusOfficeType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CampusOfficeType  $campusOfficeType
     * @return \Illuminate\Http\Response
     */
    public function edit(CampusOfficeType $campusOfficeType)
    {
        return view('settings.campus_office_types.campus_types', ['campusOfficeType' => $campusOfficeType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CampusOfficeType  $campusOfficeType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CampusOfficeType $campusOfficeType)
    {
        $request->validate([
            'type' => 'required',
        ]);

        $campusOfficeType->update($request->all());

        return redirect()->route('campusOfficeType.index')
            ->with('success', 'Campus/Office type has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CampusOfficeType  $campusOfficeType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CampusOfficeType $campusOfficeType)
    {
        try {
            return $campusOfficeType->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
