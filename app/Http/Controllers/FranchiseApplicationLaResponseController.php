<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\FranchiseApplication;
use Illuminate\Database\QueryException;
use App\Models\FranchiseApplicationLaResponse;
use App\Models\FranchiseApplicationsAttachment;
use App\Models\FranchiseApplicationAttachmentType;

class FranchiseApplicationLaResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['franchise_application_id'] = $request->franchise_application_id;

        if ($request->ajax()) {
            $query = FranchiseApplicationLaResponse::with(['user','forwarded_user'])->where('franchise_application_id', $data['franchise_application_id'])->get();
            //dd($query->toArray());
            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('review_by', function ($row) {
                    return $row['user']['name'];
                })
                ->addColumn('forwarded', function ($row) {
                    return $row['forwarded_user']['name'];
                })
                ->addColumn('forwarded_date', function ($row) {
                    return $row['forwarded_date'] ? Carbon::parse($row['forwarded_date'])->format('d-m-Y') : '';
                })
                ->addColumn('status', function ($row) {
                    return ucwords(str_replace('_', ' ', $row['status']));
                })
                ->addColumn('action', function ($row) {
                    return view('franchise_application.legal_response_action', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }



        return view('franchise_application.franchise_application_legal_response', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($franchise_application_id)
    {
        $data['franchise_application_id'] = $franchise_application_id;
        $data['franchise_application'] = FranchiseApplication::find($data['franchise_application_id']);
        $data['users'] = Employee::with('user')->where('branch_id', 2)->where('department_id', 8)->whereNull('left_date')->orderBy('preferred_name')->get(['user_id','preferred_name'])->toArray();
        $data['forwarded_to'] = Employee::with('user')->where('branch_id', 2)->whereIn('department_id', [6,5,9])->whereNull('left_date')->orderBy('preferred_name')->get(['user_id','preferred_name'])->toArray();
        $data['attachment_types'] = FranchiseApplicationAttachmentType::all();
        $data['departments'] = Department::all();
        return view('franchise_application.franchise_application_legal_response', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $franchise_application_la = FranchiseApplicationLaResponse::create($request->all());

        if ($request->ajax()) {
            return $franchise_application_la;
        } else {
            return redirect()->back()->with('success', 'Application/Form submitted successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FranchiseApplicationLaResponse  $franchiseApplicationLa
     * @return \Illuminate\Http\Response
     */
    public function show(FranchiseApplicationLaResponse $franchiseApplicationLa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FranchiseApplicationLaResponse  $franchiseApplicationLa
     * @return \Illuminate\Http\Response
     */
    public function edit(FranchiseApplicationLaResponse $franchiseApplicationLa)
    {
        $data['franchise_application_id'] = $franchiseApplicationLa->franchise_application_id;
        $data['franchise_application'] = FranchiseApplication::find($data['franchise_application_id']);
        $data['users'] = Employee::with('user')->where('branch_id', 2)->where('department_id', 8)->whereNull('left_date')->orderBy('preferred_name')->get(['user_id','preferred_name'])->toArray();
        $data['forwarded_to'] = Employee::with('user')->where('branch_id', 2)->whereIn('department_id', [6,5,9])->whereNull('left_date')->orderBy('preferred_name')->get(['user_id','preferred_name'])->toArray();
        $data['franchiseApplicationLa'] = $franchiseApplicationLa;
        $data['attachment_types'] = FranchiseApplicationAttachmentType::all();
        $data['departments'] = Department::all();
        return view('franchise_application.franchise_application_legal_response', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FranchiseApplicationLaResponse  $franchiseApplicationLa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FranchiseApplicationLaResponse $franchiseApplicationLa)
    {
        $franchiseApplicationLa->update($request->all());

        if ($request->ajax()) {
            return $franchiseApplicationLa;
        } else {
            return redirect()->route('franchise-application-la.create', $franchiseApplicationLa->franchise_application_id)->with('success', 'Application/Form Updated successfully');
        }
    }

    public function applicationDocuments(Request $request)
    {

        $data['franchise_application_id'] = $request->franchise_application_id;
        //dd($data['franchise_application_id']);
        if ($request->ajax()) {
            $id = $data['franchise_application_id'];
            $details = FranchiseApplicationsAttachment::with(['user.employee','attachment_type'])->where(['franchise_application_id' => $data['franchise_application_id']]);
            //dd($details->get()->toArray());
            if (! auth()->user()) {
                $details = $details->whereNull('uploaded_by');
            }

            if (isset($request->attachment_type_id) && $request->attachment_type_id > 0) {
                $details = $details->where('attachment_type_id', $request->attachment_type_id);
            }

            if (isset($request->department_id) && $request->department_id > 0) {
                $details = $details->whereHas('user', function ($query) use ($request) {
                    $query->whereHas('employee', function ($query) use ($request) {
                        $query->where('department_id', $request->department_id);
                    });
                });
            }
            $data = $details->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('file_name', function ($row) use ($request) {
                        return view('franchise_application.document_path', ['row' => $row,'id' => $request->franchise_application_id]);
                    })
                    ->addColumn('document_type', function ($row) {
                        return $row['attachment_type']['name'];
                    })
                    ->addColumn('department', function ($row) {
                        $department = Employee::where('user_id', $row['user']['id'])->first('department_id');
                        $dept_name = Department::where('id', $department->department_id)->first('department_name');
                        return $row['user'] ? $dept_name->department_name : '-';
                    })
                    ->addColumn('uploaded_by', function ($row) {
                        return $row['user'] ? $row['user']['name'] : '-';
                    })
                    ->addColumn('uploaded_date', function ($row) {
                        return  $row['uploaded_date'] ? Carbon::parse($row['uploaded_date'])->format('d-m-Y') : '-';
                    })
                    ->addColumn('details', function ($row) {
                        return $row['details'];
                    })
                    ->make(true);
        }

        return view('franchise_application.franchise_application_documents', compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FranchiseApplicationLaResponse  $franchiseApplicationLa
     * @return \Illuminate\Http\Response
     */
    public function destroy(FranchiseApplicationLaResponse $franchiseApplicationLa)
    {
        try {
            return $franchiseApplicationLa->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
