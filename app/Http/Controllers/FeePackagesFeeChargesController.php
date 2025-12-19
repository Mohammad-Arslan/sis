<?php

namespace App\Http\Controllers;

use App\Models\FeeCharge;
use App\Models\FeePackage;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\FeePackagesFeeCharges;

class FeePackagesFeeChargesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->id;
        // $classes = ComClass::get();
        $academic_year_id = $request->academic_year_id;
        $fee_package = FeePackage::where('id', '=', $id)->get();
        $fee_charges = FeeCharge::with('fee_charges_type')->where('branch_id', '=', $fee_package[0]->branch_id)->where('academic_year_id', $academic_year_id)->get();
        $fee_packages_fee_charges = FeePackagesFeeCharges::where('fee_package_id', '=', $fee_package[0]->id)
            ->where('status', '=', '1')
            ->pluck('fee_charge_id')->toArray();
        // dd($fee_packages_fee_charges);
        return view(
            'settings.fee_packages.fee_charges_modal',
            [
                'fee_package' => $fee_package,
                'fee_charges' => $fee_charges,
                'fee_packages_fee_charges' => $fee_packages_fee_charges
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'fee_package_id' => 'required',
            'fee_charge_id' => 'required',
        ]);
        // dd($request->all());

        $fee_charge = FeeCharge::where('id', $request->fee_charge_id);
        $data = FeePackagesFeeCharges::where('fee_package_id', '=', $request->fee_package_id)
            ->where('fee_charge_id', '=', $request->fee_charge_id)->first();
        if ($data !== null) {
            if ($data->status == 0) {
                $data->status = 1;
                $fee_charge->update(['fee_package_id' => $request->fee_package_id]);
            } else {
                $data->status = 0;
                $fee_charge->update(['fee_package_id' => null]);
            }
            $data->save();
        } else {
            FeePackagesFeeCharges::create([
                'fee_charge_id' => $request->fee_charge_id,
                'fee_package_id' => $request->fee_package_id,
                'status' => 1
            ]);
            $fee_charge->update(['fee_package_id' => $request->fee_package_id]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FeePackagesFeeCharges  $feePackagesFeeCharges
     * @return \Illuminate\Http\Response
     */
    public function show(FeePackagesFeeCharges $feePackagesFeeCharges)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeePackagesFeeCharges  $feePackagesFeeCharges
     * @return \Illuminate\Http\Response
     */
    public function edit(FeePackagesFeeCharges $feePackagesFeeCharges)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeePackagesFeeCharges  $feePackagesFeeCharges
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeePackagesFeeCharges $feePackagesFeeCharges)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeePackagesFeeCharges  $feePackagesFeeCharges
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeePackagesFeeCharges $feePackagesFeeCharges)
    {
        //
    }
}
