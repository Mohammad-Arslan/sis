<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Branch;
use App\Models\Company;
use App\Models\FeeCharge;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\FeeChargesType;
use Illuminate\Database\QueryException;

class FeeChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FeeCharge::with(['fee_charges_type', 'company', 'branch', 'academic_year']);

            if ($request->academic_year_id && $request->academic_year_id > 0) {
                $data = $data->whereHas('academic_year', function ($query) use ($request) {
                    $query->where('id', $request->academic_year_id);
                });
            }

            if ($request->company_id && $request->company_id > 0) {
                $data = $data->whereHas('company', function ($query) use ($request) {
                    $query->where('id', $request->company_id);
                });
            }

            if ($request->branch_id && $request->branch_id > 0) {
                $data = $data->whereHas('branch', function ($query) use ($request) {
                    $query->where('id', $request->branch_id);
                });
            }

            if ($request->charges_id && $request->charges_id > 0) {
                $data = $data->whereHas('fee_charges_type', function ($query) use ($request) {
                    $query->where('id', $request->charges_id);
                });
            }

            if ($request->searchName && $request->searchName != null) {
                $data = $data->where(function ($query) use ($request) {
                    $query->orWhere('amount', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('created_at', 'like', '%' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('company', function ($query) use ($request) {
                    $query->where('company_name', 'like', '%' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('branch', function ($query) use ($request) {
                    $query->where('br_name', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('branch_code', 'like', '%' . $request->searchName . '%');
                });


                $data = $data->orWhereHas('fee_charges_type', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('frequency', 'like', '%' . $request->searchName . '%');
                });
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('academic_year', function ($row) {
                    return $row['academic_year'] ? $row['academic_year']['title'] : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('settings.fee_charges.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $academic_years = AcademicYear::all();
        $fee_charges_type = FeeChargesType::get();
        $companies = Company::get();
        $branches = Branch::get();

        return view('settings.fee_charges.fee_charges', ['fee_charges_type' => $fee_charges_type, 'companies' => $companies, 'branches' => $branches, 'academic_years' => $academic_years]);
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

    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'required|exists:branches,id',
            'fee_charges_type_id' => 'required|exists:fee_charges_types,id',
            'amount' => 'required|numeric|min:0',
            'is_discountable' => 'required|boolean',
            'is_refundable' => 'required|boolean',
        ]);

        $exists = FeeCharge::where([
            'academic_year_id' => $request->academic_year_id,
            'branch_id' => $request->branch_id,
            'fee_charges_type_id' => $request->fee_charges_type_id,
        ])->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fee Charge already exists for the selected Branch, Academic Year, and Charge Type.');
        }

        FeeCharge::create($request->all());

        return redirect()->route('fee-charges.index')
            ->with('success', 'Fee Charges created successfully.');
    }

    /**
     * Store multiple fee charges at once
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'required|exists:branches,id',
            'fee_charges' => 'required|array|min:1',
            'fee_charges.*.fee_charges_type_id' => 'required|exists:fee_charges_types,id',
            'fee_charges.*.amount' => 'required|numeric|min:0',
            'fee_charges.*.is_discountable' => 'required|boolean',
            'fee_charges.*.is_refundable' => 'required|boolean',
        ]);

        $createdCount = 0;
        $skippedCount = 0;
        $errors = [];

        foreach ($request->fee_charges as $index => $feeChargeData) {
            // Check if fee charge already exists
            $exists = FeeCharge::where([
                'academic_year_id' => $request->academic_year_id,
                'branch_id' => $request->branch_id,
                'fee_charges_type_id' => $feeChargeData['fee_charges_type_id'],
            ])->exists();

            if ($exists) {
                $skippedCount++;
                $feeChargeType = FeeChargesType::find($feeChargeData['fee_charges_type_id']);
                $errors[] = "Row " . ($index + 1) . ": Fee Charge '{$feeChargeType->name}' already exists for the selected Branch, Academic Year, and Charge Type.";
                continue;
            }

            try {
                FeeCharge::create([
                    'academic_year_id' => $request->academic_year_id,
                    'company_id' => $request->company_id,
                    'branch_id' => $request->branch_id,
                    'fee_charges_type_id' => $feeChargeData['fee_charges_type_id'],
                    'amount' => $feeChargeData['amount'],
                    'is_discountable' => $feeChargeData['is_discountable'],
                    'is_refundable' => $feeChargeData['is_refundable'],
                ]);
                $createdCount++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        $message = "Successfully created {$createdCount} fee charge(s).";
        if ($skippedCount > 0) {
            $message .= " Skipped {$skippedCount} duplicate(s).";
        }

        if (! empty($errors)) {
            return redirect()->back()
                ->withInput()
                ->with('warning', $message)
                ->with('errors', $errors);
        }

        return redirect()->route('fee-charges.index')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FeeCharge  $feeCharge
     * @return \Illuminate\Http\Response
     */
    public function show(FeeCharge $feeCharge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeeCharge  $feeCharge
     * @return \Illuminate\Http\Response
     */
    public function edit(FeeCharge $feeCharge)
    {
        $academic_years = AcademicYear::all();
        $fee_charges_type = FeeChargesType::get();
        $companies = Company::get();
        $branches = Branch::get();
        return view('settings.fee_charges.fee_charges', ['fee_charges_type' => $fee_charges_type, 'feeCharge' => $feeCharge, 'companies' => $companies, 'branches' => $branches, 'academic_years' => $academic_years]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeeCharge  $feeCharge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeeCharge $feeCharge)
    {
        $request->validate([
            'academic_year_id' => 'required',
            'company_id' => 'required',
            'branch_id' => 'required',
            'fee_charges_type_id' => 'required',
            'amount' => 'required',
            'is_discountable' => 'required'
        ]);

        $feeCharge->update($request->all());

        return redirect()->route('fee-charges.index')
            ->with('success', 'Fee Charges updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeeCharge  $feeCharge
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeeCharge $feeCharge)
    {
        try {
            return $feeCharge->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
