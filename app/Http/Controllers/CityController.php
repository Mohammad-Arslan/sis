<?php

namespace App\Http\Controllers;

use App\Exports\ExportCity;
use App\Models\City;
use App\Models\State;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use DataTables;
//use Maatwebsite\Excel\Excel;
use App\Imports\ImportCity;
use Excel;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = City::with('states');
            //dd($data->get()->toArray());
            if ($request->state_id && $request->state_id > 0) {
                $data = $data->whereHas('states', function ($query) use ($request) {
                    $query->where('id', $request->state_id);
                });
            }
            if ($request->searchName && $request->searchName != null) {
                //dd($request->searchName);

                $data = $data->where(function($query) use ($request){
                    $query->orWhere('city_name', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('abbreviation', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('created_at', 'like', '%' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('states', function ($query) use ($request) {
                     $query->where('state_name', 'like', '' . $request->searchName . '%');
                });
            }
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.cities.actions',['row'=>$row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $states = State::get();
        return view('settings.cities.cities', compact('states'));
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
        // dd($request->toArray());
        $request->validate([
            'city_name' => 'required|unique:cities,city_name',
            'abbreviation' => 'required',
            'state_id' => 'required',
        ]);
        // dd($request);
        City::create($request->all());

        return redirect()->route('cities.index')
            ->with('success', 'City created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        //        dd($city->toArray());
        $states = State::get();
        return view('settings.cities.cities', ['city' => $city, 'states' => $states]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $request->validate([
            'city_name' => 'required|unique:cities,city_name,' . $city->id,
            'abbreviation' => 'required',
            'state_id' => 'required',
        ]);

        $city->update($request->all());

        return redirect()->route('cities.index')
            ->with('success', 'City updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        try {
            return $city->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function importCity(Request $request){
        Excel::import(new ImportCity,$request->file('file')->store('files'));
        return redirect()->back();
    }

    public function exportCity(Request $request){
        return Excel::download(new ExportCity,'cities.xlsx');
    }
}
