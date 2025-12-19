<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DesignationType;
use Illuminate\Database\QueryException;

class DesignationTypeController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function edit(DesignationType $designation_type)
    {
    }

    public function update(Request $request, DesignationType $designation_type)
    {
    }

    public function destroy(DesignationType $designation_type)
    {
        try {
            return $designation_type->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
