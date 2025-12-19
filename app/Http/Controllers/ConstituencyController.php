<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Constituency;
use Illuminate\Http\Request;

class ConstituencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Constituency  $constituency
     * @return \Illuminate\Http\Response
     */
    public function show(Constituency $constituency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Constituency  $constituency
     * @return \Illuminate\Http\Response
     */
    public function edit(Constituency $constituency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Constituency  $constituency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Constituency $constituency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Constituency  $constituency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Constituency $constituency)
    {
        //
    }

    public function get_state_constituencies(Request $request)
    {

        try {
            if (isset($request->id)) {
                $data = Constituency::where('state_id', $request->id)->get();
            } else {
                $data = Constituency::all();
            }

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully','data' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => []]);
        }
    }
}
