<?php

namespace App\Http\Controllers;

use App\Models\TaxType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TaxTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TaxType::all();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                // ->addColumn('action', function ($row) {
                //     return view('students.guardian_actions', ['row' => $row]);
                // })
                // ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.tax_type.tax_type');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
        ]);

        TaxType::create($request->all());
        return redirect()->back()->with(['success', 'Tax Type created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaxType  $taxType
     * @return \Illuminate\Http\Response
     */
    public function show(TaxType $taxType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaxType  $taxType
     * @return \Illuminate\Http\Response
     */
    public function edit(TaxType $taxType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaxType  $taxType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaxType $taxType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaxType  $taxType
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaxType $taxType)
    {
        //
    }
}
