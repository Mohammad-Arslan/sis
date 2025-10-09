<?php

namespace App\Http\Controllers;

use App\Exports\ExportClass;
use App\Imports\ImportCountry;
use App\Models\Country;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use DataTables;
use Excel;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Country::get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        return view('settings.countries.actions', ['row'=>$row]);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('settings.countries.countries');
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
            'country_name' => 'required|unique:countries,country_name',
            'abbreviation' => 'required',
            'country_code' => 'required',
        ]);

        Country::create($request->all());

        return redirect()->route('countries.index')
            ->with('success', 'Country created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        return view('settings.countries.countries', ['country' => $country]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $request->validate([
            'country_name' => 'required|unique:countries,country_name,' . $country->id,
            'abbreviation' => 'required',
            'country_code' => 'required',
        ]);

        $country->update($request->all());

        return redirect()->route('countries.index')
            ->with('success', 'Country updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        try {
            return $country->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function importCountry(Request $request){
        Excel::import(new ImportCountry,$request->file('file')->store('files'));
        return redirect()->back();
    }

    public function exportCountry(Request $request){
        return Excel::download(new ExportClass(),'classes.xlsx');
    }
}
