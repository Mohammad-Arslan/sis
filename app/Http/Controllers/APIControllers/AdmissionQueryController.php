<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdmissionQueryValidation;
use App\Models\AdmissionQuery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdmissionQueryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(AdmissionQueryValidation $request)
    {
        try {
            $data = AdmissionQuery::store_update_admission_query($request->all());

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Saved Successfully.','data' => $data]);

        } catch (\Exception $exception) {
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => new \stdClass()]);
        }
    }

}
