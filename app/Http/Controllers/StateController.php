<?php

namespace App\Http\Controllers;

use App\Exports\ExportState;
use App\Imports\ImportState;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DataTables;
use Excel;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = State::with('countries')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.states.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $countries = Country::get();
        return view('settings.states.states', compact('countries'));
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
            'state_name' => 'required|unique:states,state_name',
            'country_id' => 'required',
        ]);

        State::create($request->all());

        return redirect()->route('states.index')
            ->with('success', 'State created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\State $state
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\State $state
     * @return \Illuminate\Http\Response
     */
    public function edit(State $state)
    {
        $countries = Country::get();
        return view('settings.states.states', ['state' => $state, 'countries' => $countries]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\State $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        $request->validate([
            'state_name' => 'required|unique:states,state_name,' . $state->id,
            'country_id' => 'required',
        ]);

        $state->update($request->all());

        return redirect()->route('states.index')
            ->with('success', 'State updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\State $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        try {
            return $state->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function importState(Request $request)
    {
        Excel::import(new ImportState, $request->file('file')->store('files'));
        return redirect()->back();
    }

    public function exportState(Request $request)
    {
        return Excel::download(new ExportState, 'states.xlsx');
    }
}
