<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Town;
use App\Models\Guardian;
use App\Models\Language;
use App\Models\Nationality;
use App\Models\Relation;
use App\Models\Religion;
use App\Models\StudentAddress;
use App\Models\Student;
use Illuminate\Http\Request;
use Validator;

class StudentAddressController extends Controller
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
        $request->validate([
            "country_id" => 'required',
            "state_id" => 'required',
            "city_id" => 'required',
            "town_id" => 'required',
            "postal_code" => 'required|max:255',
            "contact_person" => 'required|max:255',
            "phone" => 'required|max:255',
            "sms_number" => 'required|max:255',
            "residential_mobile" => 'required|max:255',
            "street_address" => 'required|max:255',
            "per_city_id" => 'required',
            "per_phone" => 'required|max:255',
            "per_postal_code" => 'required|max:255',
            "per_address" => 'required|max:255',
            "student_id" => 'required'
        ]);

        $student = Student::find($request->input('student_id'));
        $studentAddress = $student->student_address()->create([
            'res_country_id' => $request->country_id,
            'res_state_id' => $request->state_id,
            'res_city_id' => $request->city_id,
            'res_town_id' => $request->town_id,
            'res_postal_code' => $request->postal_code,
            'res_contact_person' => $request->contact_person,
            'res_phone' => $request->phone,
            'res_sms_number' => $request->sms_number,
            'res_mobile' => $request->residential_mobile,
            'street_address' => $request->street_address,
            'per_city_id' => $request->per_city_id,
            'per_phone' => $request->per_phone,
            'per_postal_code' => $request->per_postal_code,
            'per_address' => $request->per_address,
        ]);

        return redirect(route('students.edit', $student->id) . '?tab=correspondence')->with('success', 'Student correspondence added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentAddress  $studentAddress
     * @return \Illuminate\Http\Response
     */
    public function show(StudentAddress $studentAddress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentAddress  $studentAddress
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentAddress $studentAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentAddress  $studentAddress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentAddress $studentAddress)
    {
        // dd($request->all());

        $request->validate([
            "country_id" => 'required',
            "state_id" => 'required',
            "city_id" => 'required',
            "town_id" => 'required',
            "postal_code" => 'required|max:255',
            "contact_person" => 'required|max:255',
            "phone" => 'required|max:255',
            "sms_number" => 'required|max:255',
            "residential_mobile" => 'required|max:255',
            "street_address" => 'required|max:255',
            "per_city_id" => 'required',
            "per_phone" => 'required|max:255',
            "per_postal_code" => 'required|max:255',
            "per_address" => 'required|max:255',
            "student_id" => 'required'
        ]);

        $studentAddress->update([
            'res_country_id' => $request->country_id,
            'res_state_id' => $request->state_id,
            'res_city_id' => $request->city_id,
            'res_town_id' => $request->town_id,
            'res_postal_code' => $request->postal_code,
            'res_contact_person' => $request->contact_person,
            'res_phone' => $request->phone,
            'res_sms_number' => $request->sms_number,
            'res_mobile' => $request->residential_mobile,
            'street_address' => $request->street_address,
            'per_city_id' => $request->per_city_id,
            'per_phone' => $request->per_phone,
            'per_postal_code' => $request->per_postal_code,
            'per_address' => $request->per_address,
        ]);

        return redirect(route('students.edit', $request->student_id) . '?tab=correspondence')->with('success', 'Student correspondence updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentAddress  $studentAddress
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentAddress $studentAddress)
    {
        //
    }
}
