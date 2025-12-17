<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ParentQuery;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ParentQueryController extends Controller
{
    public function index(Request $request)
    {
        $branch_id = 0;
        if (!isSuperAdmin() && !isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $branches = Branch::where('id', $branch_id)->get();
        } else {
            $branches = Branch::all();
        }
        if ($request->ajax()) {
            $data = ParentQuery::with(['guardian', 'student.branch']);
            if ($branch_id != 0) {
                $data = $data->whereHas('student', function ($q) use ($branch_id) {
                    $q->where('branch_id', $branch_id);
                });
            }
            if (isset($request->branch_id)) {
                $data = $data->whereHas('student', function ($q) use ($request) {
                    $q->where('branch_id', $request->branch_id);
                });
            }
            if (isset($request->type)) {
                $data = $data->where('type', 'LIKE', $request->type);
            }
            if (isset($request->date_range) && $request->date_range != '') {
                $dates = explode(" to ", $request->date_range);
                if (count($dates) > 1) {
                    $data = $data->whereDate('created_at', '>=', $dates[0])
                        ->whereDate('created_at', '<=', $dates[1]);
                } else {
                    $data = $data->whereDate('created_at', $dates[0]);
                }
            }
            $data = $data->get();
            return Datatables::of($data)
                ->addColumn('std_name', function ($row) {
                    return $row['student']['first_name'] . ' ' . $row['student']['middle_name'] . ' ' . $row['student']['last_name'];
                })
                ->rawColumns(['std_name'])
                ->make(true);
        }

        return view('settings.parent_queries.index', ['branches' => $branches]);
    }
}
