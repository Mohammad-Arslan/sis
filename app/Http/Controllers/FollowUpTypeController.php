<?php

namespace App\Http\Controllers;

use App\Models\FollowUpType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FollowUpTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = FollowUpType::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.followup_types.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        return view('settings.followup_types.followup_types');
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
            'follow_up_type' => 'required',
        ]);
        $follow_up_type = FollowUpType::where('follow_up_type',$request->follow_up_type)->get();
        if(isset($follow_up_type[0]))
        {
            return redirect()->route('followUpType.index')
            ->with('error', 'Duplicate entries not allowed.');
        }

        FollowUpType::create($request->all());

        return redirect()->route('followUpType.index')
            ->with('success', 'Follow Up type has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FollowUpType  $followUpType
     * @return \Illuminate\Http\Response
     */
    public function show(FollowUpType $followUpType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FollowUpType  $followUpType
     * @return \Illuminate\Http\Response
     */
    public function edit(FollowUpType $followUpType)
    {
       
        return view('settings.followup_types.followup_types', ['followUpType' => $followUpType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FollowUpType  $followUpType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FollowUpType $followUpType)
    {
        $request->validate([
            'follow_up_type' => 'required',
        ]);

        $followUpType->update($request->all());

        return redirect()->route('followUpType.index')
            ->with('success', 'Follow up type has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FollowUpType  $followUpType
     * @return \Illuminate\Http\Response
     */
    public function destroy(FollowUpType $followUpType)
    {
        try {
            return $followUpType->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
