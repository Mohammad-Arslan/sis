<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\ComClass;
use App\Models\GeneralDocument;
use App\Models\State;
use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BooklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $booklists = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->where('slug', 'booklist');
                }
            )->where('status', 'active');

            $booklists = GeneralDocument::filteration($request, $booklists);

            $booklists = $booklists->get();

            return DataTables::of($booklists)
                ->addIndexColumn()
                ->addColumn('class_name', function ($row) {
                    return $row['com_class'] ? $row['com_class']['class_name'] : '-';
                })
                ->addColumn('state_name', function ($row) {
                    return $row['state'] ? $row['state']['state_name'] : '-';
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
                    return view('booklist.action', ['row' => $row]);
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        $data = GeneralDocument::filterationDropdownData();

        return view('booklist.index', $data);
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
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
