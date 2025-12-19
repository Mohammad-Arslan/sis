<?php

namespace App\Http\Controllers;

use App\Models\AttachmentType;
use App\Models\Branch;
use App\Models\GeneralDocument;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BrandingMarketingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $marketing_documents = GeneralDocument::whereHas(
                'attachment_type',
                function ($q) {
                    $q->where('slug', 'branding_marketing');
                }
            )->where('status', 'active');

            $marketing_documents = GeneralDocument::filteration($request, $marketing_documents);

            $marketing_documents = $marketing_documents->get();

            return DataTables::of($marketing_documents)
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
                    return view('branding_marketing.action', ['row' => $row]);
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('branding_marketing.index');
    }
}
