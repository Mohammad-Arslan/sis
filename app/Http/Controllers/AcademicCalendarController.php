<?php

namespace App\Http\Controllers;

use App\Models\GeneralDocument;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AcademicCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()){

            $academic_calendar = GeneralDocument::whereHas(
                'attachment_type' , function($q){
                $q->where('slug','academic_calendar');
            })->where('status','active');

            $academic_calendar = GeneralDocument::filteration($request,$academic_calendar);

            $academic_calendar = $academic_calendar->get();

            return DataTables::of($academic_calendar)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    return $row['attachment_type']['name'];
                })
                ->addColumn('branch_name', function ($row) {
                    return $row['branch'] ? $row['branch']['br_name'] : 'N/A';
                })
                ->addColumn('uploaded_by', function ($row) {
                    return $row['user']['name'];
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks ? $row->remarks : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('academic_calendar.action', ['row' => $row]);
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('academic_calendar.index');
    }
}
