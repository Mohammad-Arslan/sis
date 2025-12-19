<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Requests\FranchiseInquiryValiadtion;
use App\Models\City;
use App\Models\FranchiseInquiry;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FranchiseInquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FranchiseInquiryValiadtion $request)
    {
        try {
            $data = FranchiseInquiry::store_update_franchise_inquiry($request->all());

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Saved Successfully.','data' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => new \stdClass()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FranchiseInquiry $franchisesInquiry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FranchiseInquiry $franchisesInquiry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FranchiseInquiry $franchisesInquiry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FranchiseInquiry $franchisesInquiry)
    {
        //
    }
}
