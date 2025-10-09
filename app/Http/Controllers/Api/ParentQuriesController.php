<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ParentQuery;
use Illuminate\Http\Request;

class ParentQuriesController extends Controller
{
    public function saveQuries(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'guardian_id' => 'required',
            'type' => 'required',
            'remarks' => 'required'
        ]);
        $input = $request->all();
        ParentQuery::create($input);
        return response('Requested Successfully', 200);
    }
}
