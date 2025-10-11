<?php

namespace App\Http\Controllers;

use App\Exports\ExportTown;
use App\Imports\ImportTown;
use App\Models\City;
use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DataTables;
use Excel;

class TownController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Town::with('cities')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.towns.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        $cities = City::get();
        return view('settings.towns.towns', compact('cities'));
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
            'town_name' => 'required',
            'city_id' => 'required',
        ]);

        Town::create($request->all());

        return redirect()->route('towns.index')
            ->with('success', 'Town created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Town $town
     * @return \Illuminate\Http\Response
     */
    public function show(Town $town)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Town $town
     * @return \Illuminate\Http\Response
     */
    public function edit(Town $town)
    {
        $cities = City::get();
        return view('settings.towns.towns', ['town' => $town, 'cities' => $cities]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Town $town
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Town $town)
    {
        $request->validate([
            'town_name' => 'required|unique:towns,town_name,' . $town->id,
            'city_id' => 'required',
        ]);

        $town->update($request->all());

        return redirect()->route('towns.index')
            ->with('success', 'Town updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Town $town
     * @return \Illuminate\Http\Response
     */
    public function destroy(Town $town)
    {
        try {
            return $town->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function importTown(Request $request)
    {
        Excel::import(new ImportTown, $request->file('file')->store('files'));
        return redirect()->back();
    }

    public function exportTown(Request $request)
    {
        return Excel::download(new ExportTown, 'towns.xlsx');
    }
}
