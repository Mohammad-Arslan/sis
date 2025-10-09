<?php

namespace App\Http\Controllers;

use App\Models\InquiriesType;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DataTables;

class InquiriesTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = InquiriesType::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.inquiry_types.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        return view('settings.inquiry_types.inquiry_types');
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
        $type = InquiriesType::where('type',$request->type)->get();
        if(isset($type[0]))
        {
            return redirect()->route('inquiry-type.index')
            ->with('error', 'Duplicate entries not allowed.');
        }

        InquiriesType::create($request->all());

        return redirect()->route('inquiry-type.index')
            ->with('success', 'Admission inquiry type created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InquiriesType  $inquiry_type
     * @return \Illuminate\Http\Response
     */
    public function show(InquiriesType $inquiry_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InquiriesType  $inquiry_type
     * @return \Illuminate\Http\Response
     */
    public function edit(InquiriesType $inquiry_type)
    {
        return view('settings.inquiry_types.inquiry_types', ['inquiry_type' => $inquiry_type]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InquiriesType  $inquiry_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InquiriesType $inquiry_type)
    {
        $request->validate([
            'type' => 'required',
        ]);

        $inquiry_type->update($request->all());

        return redirect()->route('inquiry-type.index')
            ->with('success', 'Inquiry type has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InquiriesType  $inquiry_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(InquiriesType $inquiry_type)
    {
        try {
            return $inquiry_type->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
