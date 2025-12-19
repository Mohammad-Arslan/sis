<?php

namespace App\Http\Controllers;

use App\Models\ComClass;
use App\Models\GeneralBehaviour;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GeneralBehaviourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = GeneralBehaviour::with(['parent','com_class']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('parent', function ($row) {
                    return $row['parent'] ? $row['parent']['title'] : '';
                })
                ->addColumn('class_name', function ($row) {
                    return $row['com_class'] ? $row['com_class']['class_name'] : '';
                })
                ->addColumn('action', function ($row) {
                    return view('settings.general_behaviour.action', ['row' => $row]);
                })
                ->rawColumns(['parent','status','action'])
                ->make(true);
        }

        $data['general_behaviours'] = GeneralBehaviour::where('status', 'active')->get();
        $data['classes'] = ComClass::OrderBy('sort')->get();

        return view('settings.general_behaviour.index', $data);
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
            "title" => "required",
            "parent_id" => "required_if:has_parent,1",
            "status" => "required",
        ]);

        GeneralBehaviour::create($request->all());

        return redirect()->back()->with(['success', 'General Behaviour created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GeneralBehaviour  $generalBehaviour
     * @return \Illuminate\Http\Response
     */
    public function show(GeneralBehaviour $generalBehaviour)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GeneralBehaviour  $generalBehaviour
     * @return \Illuminate\Http\Response
     */
    public function edit(GeneralBehaviour $generalBehaviour)
    {
        $data['general_behaviours'] = GeneralBehaviour::where('status', 'active')->get();
        $data['generalBehaviour'] = $generalBehaviour;
        $data['classes'] = ComClass::OrderBy('sort')->get();

        return view('settings.general_behaviour.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GeneralBehaviour  $generalBehaviour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GeneralBehaviour $generalBehaviour)
    {
        $request->validate([
            "title" => "required",
            "parent_id" => "required_if:has_parent,1",
            "status" => "required",
        ]);

        $generalBehaviour->update($request->all());

        return redirect()->route('general-behaviour.index')->with(['success', 'General Behaviour updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GeneralBehaviour  $generalBehaviour
     * @return \Illuminate\Http\Response
     */
    public function destroy(GeneralBehaviour $generalBehaviour)
    {
        //
    }
}
