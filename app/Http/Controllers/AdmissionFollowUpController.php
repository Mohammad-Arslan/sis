<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\FollowUpType;
use Illuminate\Http\Request;
use App\Models\AdmissionFollowUp;
use Yajra\DataTables\DataTables;

class AdmissionFollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            $data['data'] = AdmissionFollowUp::where('admission_query_id', $request->admission_query_id)->with(
                [
                    'admission_query',
                    'admission_query.city',
                    'admission_query.town',
                    'admission_query.branch',
                    'admission_query.com_class',
                    'admission_query.source',
                    'admission_query.academic_year',
                    'admission_query.inquiry_type',
                    'followup_type',
                    'user'
                ]
            )->get();
            $data['followup_types'] = FollowUpType::all();
            return view('admission_queries.admission_follow_up', $data);
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
            'remarks' => 'required',
            'followup_type_id' => 'required',
            'admission_query_id' => 'required',
            'user_id' => 'required',
        ]);

        //dd($request->all());
        AdmissionFollowUp::create($request->all());

        return redirect()->route('admissionFollowUp.index', ['admission_query_id' => $request->admission_query_id])
            ->with('success', 'Follow Up has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdmissionFollowUp  $admissionFollowUp
     * @return \Illuminate\Http\Response
     */
    public function show(AdmissionFollowUp $admissionFollowUp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdmissionFollowUp  $admissionFollowUp
     * @return \Illuminate\Http\Response
     */
    public function edit(AdmissionFollowUp $admissionFollowUp, Request $request)
    {
        $data = AdmissionFollowUp::where('admission_query_id', $request->admission_query_id)->with([
            'admission_query.city',
            'admission_query.town',
            'admission_query.branch',
            'admission_query.com_class',
            'admission_query.source',
            'admission_query.academic_year',
            'admission_query.inquiry_type',
            'followup_type',
            'user'
        ])->get();
        $followup_types = FollowUpType::all();
        return view('admission_queries.admission_follow_up', ['admissionFollowUp' => $admissionFollowUp , 'followup_types' => $followup_types, 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdmissionFollowUp  $admissionFollowUp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdmissionFollowUp $admissionFollowUp)
    {
        $request->validate([
            'remarks' => 'required',
            'followup_type_id' => 'required',
            'admission_query_id' => 'required',
            'user_id' => 'required',
        ]);

        $admissionFollowUp->update($request->all());

        return redirect()->route('admissionFollowUp.index', ['admission_query_id' => $request->admission_query_id])
            ->with('success', 'Follow Up has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdmissionFollowUp  $admissionFollowUp
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdmissionFollowUp $admissionFollowUp)
    {
        //
    }
}
