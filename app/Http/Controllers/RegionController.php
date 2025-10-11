<?php

namespace App\Http\Controllers;

use App\Exports\ExportRegion;
use App\Imports\ImportRegion;
use App\Models\Region;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use DataTables;
use Excel;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Region::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.regions.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.regions.regions');
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'region_name' => 'required|unique:regions,region_name',
            'abbreviation' => 'required',
        ]);

        Region::create($request->all());

        return redirect()->route('regions.index')
            ->with('success', 'Region created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        return view('settings.regions.regions', ['region' => $region]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        $request->validate([
            'region_name' => 'required|unique:regions,region_name,' . $region->id,
            'abbreviation' => 'required',
        ]);

        $region->update($request->all());

        return redirect()->route('regions.index')
            ->with('success', 'Region updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        try {
            return $region->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function importRegion(Request $request)
    {
        Excel::import(new ImportRegion, $request->file('file')->store('files'));
        return redirect()->back();
    }

    public function exportRegion(Request $request)
    {
        return Excel::download(new ExportRegion(), 'regions.xlsx');
    }
}
