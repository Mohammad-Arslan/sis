<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\BranchAcademicYear;
use App\Models\Company;
use App\Models\FeePeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class FeePeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FeePeriod::with(['academic_year', 'branch']);
            if ($request->academic_year_id) {
                $data = $data->whereHas('academic_year', function ($q) use ($request) {
                    $q->where('id', $request->academic_year_id);
                });
            }
            if ($request->branch_id) {
                $data = $data->whereHas('branch', function ($q) use ($request) {
                    $q->where('id', $request->branch_id);
                });
            }
            if ($request->fee_period_id) {
                $data = $data->whereHas('fee_period', function ($q) use ($request) {
                    $q->where('fee_period_id', $request->fee_period_id);
                });
            }
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.fee_periods.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        //dd($parents->toArray());
        $companies = Company::all();
        $branches = Branch::all();
        // $academic_years = BranchAcademicYear::with('academic_year')->get();
        $academic_years = AcademicYear::all();
        // $fee_period = FeePeriod::get();
        // dd($academic_years->toArray()) ;
        $data = [
            'companies' => $companies,
            'branches' => $branches,
            'academic_years' => $academic_years
        ];

        return view('settings.fee_periods.fee_periods', $data);
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
        // dd($request->all());
        $request->validate([
            "period_name" => "required",
            "branch_id" => "required",
            "academic_year_id" => "required",
        ]);

        $academic_year = BranchAcademicYear::where(['branch_id' => $request->branch_id, 'academic_year_id' => $request->academic_year_id])->with(['academic_year', 'branch'])->first();
        // dd($academic_year->start_date);
        if ($request->auto_generated === 'on') {
            if ($request->period_gap === 'monthly') {
                $new_start_date = 0;
                FeePeriod::create([
                    'period_name' => $request->period_name,
                    'period_description' => $request->period_description,
                    'branch_id' => $request->branch_id,
                    'academic_year_id' => $request->academic_year_id,
                    'from_date' => $academic_year->start_date,
                    'to_date' => Carbon::parse($academic_year->start_date)->addMonth(1)->subDay(),
                    'issue_date' => $academic_year->start_date,
                    'due_date' => Carbon::parse($academic_year->start_date)->addDays(12),
                    'valid_date' => Carbon::parse($academic_year->start_date)->addDays(14),
                    'arrears_date' => Carbon::parse($academic_year->start_date)->addDays(13)->subMonth(),
                ]);
                for ($i = 0; $i <= 11; $i++) {
                    if ($i !== 0) {
                        $new_start_date = Carbon::parse($new_start_date !== 0 ? $new_start_date : $academic_year->start_date)->addMonth(1);
                        FeePeriod::create([
                            'period_name' => $request->period_name,
                            'period_description' => $request->period_description,
                            'branch_id' => $request->branch_id,
                            'academic_year_id' => $request->academic_year_id,
                            'from_date' => $new_start_date,
                            'to_date' => Carbon::parse($new_start_date)->addMonth(1)->subDay(),
                            'issue_date' => $new_start_date,
                            'due_date' => Carbon::parse($new_start_date)->addDays(12),
                            'valid_date' => Carbon::parse($new_start_date)->addDays(14),
                            'arrears_date' => Carbon::parse($new_start_date)->addDays(13)->subMonth(),
                        ]);
                    }
                }
            } else {
                $new_start_date = 0;
                FeePeriod::create([
                    'period_name' => $request->period_name,
                    'period_description' => $request->period_description,
                    'branch_id' => $request->branch_id,
                    'academic_year_id' => $request->academic_year_id,
                    'from_date' => $academic_year->start_date,
                    'to_date' => Carbon::parse($academic_year->start_date)->addMonth(2)->subDay(),
                    'issue_date' => $academic_year->start_date,
                    'due_date' => Carbon::parse($academic_year->start_date)->addDays(12),
                    'valid_date' => Carbon::parse($academic_year->start_date)->addDays(14),
                    'arrears_date' => Carbon::parse($academic_year->start_date)->addDays(13)->subMonth(),
                ]);
                for ($i = 0; $i <= 6; $i++) {
                    if ($i !== 0) {
                        $new_start_date = Carbon::parse($new_start_date !== 0 ? $new_start_date : $academic_year->start_date)->addMonth(2);
                        FeePeriod::create([
                            'period_name' => $request->period_name,
                            'period_description' => $request->period_description,
                            'branch_id' => $request->branch_id,
                            'academic_year_id' => $request->academic_year_id,
                            'from_date' => $new_start_date,
                            'to_date' => Carbon::parse($new_start_date)->addMonth(2)->subDay(),
                            'issue_date' => $new_start_date,
                            'due_date' => Carbon::parse($new_start_date)->addDays(12),
                            'valid_date' => Carbon::parse($new_start_date)->addDays(14),
                            'arrears_date' => Carbon::parse($new_start_date)->addDays(13)->subMonth(),
                        ]);
                    }
                }
            }
        } else {
            $request->validate([
                "from_date" => "required",
                "issue_date" => "required",
                "due_date" => "required",
                "valid_date" => "required",
                "arrears_date" => "required",
                "to_date" => "required"
            ]);
            FeePeriod::create($request->all());
        }



        return redirect()->route('fee-period.index')
            ->with('success', 'Fee Period created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FeePeriod  $feePeriod
     * @return \Illuminate\Http\Response
     */
    public function show(FeePeriod $feePeriod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeePeriod  $feePeriod
     * @return \Illuminate\Http\Response
     */
    public function edit(FeePeriod $feePeriod)
    {
        $companies = Company::all();
        $branches = Branch::all();
        $academic_years = AcademicYear::all();

        $data = [
            'fee_period' => $feePeriod->load('branch.company'),
            'companies' => $companies,
            'branches' => $branches,
            'academic_years' => $academic_years
        ];
        return view('settings.fee_periods.fee_periods', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeePeriod  $feePeriod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeePeriod $feePeriod)
    {
        $request->validate([
            "period_name" => "required",
            "branch_id" => "required",
            "academic_year_id" => "required",
            "from_date" => "required",
            "issue_date" => "required",
            "due_date" => "required",
            "valid_date" => "required",
            "arrears_date" => "required",
            "to_date" => "required"
        ]);

        $feePeriod->update($request->all());

        return redirect()->route('fee-period.index')
            ->with('success', 'Fee period updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeePeriod  $feePeriod
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeePeriod $feePeriod)
    {
        try {
            return $feePeriod->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
