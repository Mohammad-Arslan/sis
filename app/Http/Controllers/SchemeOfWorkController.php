<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\GeneralDocument;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SchemeOfWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()){

            $scheme_of_works = GeneralDocument::whereHas(
                'attachment_type' , function($q){
                $q->where('slug','scheme_of_work');
            })->where('status','active');

            $scheme_of_works = GeneralDocument::filteration($request,$scheme_of_works);

            $scheme_of_works = $scheme_of_works->get();

            return DataTables::of($scheme_of_works)
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
                ->addColumn('subject_name', function ($row) {
                    return $row['subject'] ? $row['subject']['subject_name'] : 'N/A';
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
                    return view('scheme_of_work.action', ['row' => $row]);
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        $data = GeneralDocument::filterationDropdownData();

        return view('scheme_of_work.index',$data);
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
