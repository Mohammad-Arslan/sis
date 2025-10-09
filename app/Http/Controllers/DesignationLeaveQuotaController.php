<?php

namespace App\Http\Controllers;

use App\Models\DesignationLeaveQuota;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DesignationLeaveQuotaController extends Controller
{
    public function index(Request $request)
    {

        if($request->ajax())
        {
            $data = DesignationLeaveQuota::with(['leaveType','designation']);
            //dd($data->get()->toArray());
            if ($request->desig_id && $request->desig_id > 0) {
                $data = $data->where('designation_id', $request->desig_id);
            }

            if ($request->leavetype_id && $request->leavetype_id > 0) {
                $data = $data->where('leave_type_id', $request->leavetype_id);
            }

            if($request->searchName && $request->searchName != null)
            {
                $data = $data->orWhereHas('designation', function ($query) use ($request) {
                    $query->where('designation_name', 'like', '%' . $request->searchName . '%');
                });

                $data = $data->orWhereHas('leaveType', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->searchName . '%');
                });
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('designation_name', function ($row) {
                    $designationName = $row->designation->designation_name;
                    return $designationName;
                })
                ->addColumn('leave_type', function ($row) {
                    return $row['leaveType'] ? $row['leaveType']['name'] : '';
                })
                ->addColumn('no_of_allowed_leaves', function ($row) {
                    return $row->no_of_allowed_leaves;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('action', function ($row) {
                    return view('settings.designation_leave_quotas.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.designation_leave_quotas.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'designation_id' => 'required',
            'leave_type_id' => 'required',
            'no_of_allowed_leaves' => 'required'
        ]);
        DesignationLeaveQuota::updateOrCreate([
            'designation_id' => $request->designation_id,
            'leave_type_id' => $request->leave_type_id
        ], [
            'no_of_allowed_leaves' => $request->no_of_allowed_leaves
        ]);
        return redirect()->route('designationLeaveQuota.index')->with('success', 'Designation leave quota saved successfully.');
    }

    public function show($id)
    {
        if ($id != null) {
            $leaveQuota = DesignationLeaveQuota::find($id);
            if (!empty($leaveQuota)) {
                $designationLeaveQuotas = DesignationLeaveQuota::all();
                return view('settings.designation_leave_quotas.index', compact('designationLeaveQuotas', 'leaveQuota'));
            }
        }
        return redirect()->route('designation-leave-quotas.index');
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            DesignationLeaveQuota::find($request->id)->delete();
        }
    }

    public function getEmployeeLeaveQouta($id)
    {
        $leaveQuotas = DesignationLeaveQuota::with('leaveType')->where('designation_id',$id)->get();
        return view('employees.leave_applications.leave_quotas', compact('leaveQuotas'));
    }
}
