<?php

namespace App\Http\Controllers;

use App\Models\PaperType;
use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaperTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = PaperType::with('subject');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                 ->addColumn('action', function ($row) {
                     return view('settings.paper_type.action', ['row' => $row]);
                 })
                 ->rawColumns(['status','action'])
                ->make(true);
        }

        $data['subjects'] = Subject::all();

        return view('settings.paper_type.index',$data);
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
            "name" => "required",
            "subject_id" => "required",
            "status" => "required",
        ]);

        PaperType::create($request->all());

        return redirect()->back()->with(['success', 'Paper Type created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaperType  $paperType
     * @return \Illuminate\Http\Response
     */
    public function show(PaperType $paperType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaperType  $paperType
     * @return \Illuminate\Http\Response
     */
    public function edit(PaperType $paperType)
    {
        $data['paper_type'] = $paperType;
        $data['subjects'] = Subject::all();

        return view('settings.paper_type.index',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaperType  $paperType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaperType $paperType)
    {
        $request->validate([
            "name" => "required",
            "subject_id" => "required",
            "status" => "required",
        ]);

        $paperType->update($request->all());

        return redirect()->back()->with(['success', 'Paper Type updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaperType  $paperType
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaperType $paperType)
    {
        //
    }
}
