<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\Company;
use App\Models\NetworkAssociate;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = null;

            if (isset($request->company_id)) {
                $data = Company::find($request->company_id);
            } elseif (isset($request->branch_id)) {
                $data = Branch::find($request->branch_id);
            } elseif (isset($request->network_associate_id)) {
                $data = NetworkAssociate::find($request->network_associate_id);
            }

            // If no valid data found, return empty collection
            if (! $data) {
                return DataTables::of(collect([]))->make(true);
            }

            $data = $data->bank_accounts()->get();

            return DataTables::of($data)
                ->addColumn('is_default', function ($row) {
                    $checkbox = $row->is_default == 1 ? '<input class="form-check-input" type="checkbox" name="bank_account_id[]" value="' . $row->id . '" checked style="pointer-events:none">' : '<input class="form-check-input set-is-default" type="checkbox" name="bank_account_id[]" value="' . $row->id . '">';
                    return $checkbox;
                })
                ->addColumn('action', function ($row) {
                    return view('components.bank_default_actions', ['row' => $row]);
                })
                ->rawColumns(['is_default'])
                ->make(true);
        }
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
            'bank_name' => 'required',
            // 'branch_code' => 'required',
            // 'branch_address' => 'required',
            'account_title' => 'required',
            'account_no' => 'required',
            // 'IBAN' => 'required'
        ]);

        $input = $request->all();

        if (isset($request->company_id)) {
            $data = Company::where('id', $request->company_id)->first();
        } else if (isset($request->branch_id)) {
            $data = Branch::where('id', $request->branch_id)->first();
        } else if (isset($request->nwa_id)) {
            $data = NetworkAssociate::where('id', $request->nwa_id)->first();
        }

        $input['is_default'] = $data->bank_accounts()->exists() ? 0 : 1;

        $data->bank_accounts()->create($input);

        return redirect()->back()->with('success', 'Bank Account added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BankAccount $bank_account)
    {
        $model_type = $bank_account->bank_accountable_type;
        $method = ($model_type == 'App\Models\Company' ? 'companies.edit' : ($model_type == 'App\Models\Branch' ? 'branches.edit' : ($model_type == 'App\Models\NetworkAssociate' ? 'network-associates.edit' : '')));

        // dd($method);
        return redirect(route($method, $bank_account->bank_accountable_id) . '?tab=bank')->with(['bank_account' => $bank_account]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankAccount $bank_account)
    {
        $bank_account->update($request->all());
        return redirect()->back()->with('success', 'Bank account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Make Bank as Default Bank.
     */
    public function make_bank_default(Request $request)
    {
        try {
            $bank_account = BankAccount::find($request->bank_account_id);
            BankAccount::where([['bank_accountable_id', $bank_account->bank_accountable_id], ['bank_accountable_type', $bank_account->bank_accountable_type], ['is_default', 1]])->update(['is_default' => 0]);
            $bank_account->update(['is_default' => 1]);

            return true;
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
