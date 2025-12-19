<?php

namespace App\Http\Controllers;

use App\Models\SystemModule;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class SystemModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SystemModule::with(['parent'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.system_modules.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $parents = SystemModule::where('parent_id', '<=>')->orderBy('name')->get(['id','name']);
        //dd($parents->toArray());
        return view('settings.system_modules.system_modules', ['parents' => $parents]);
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
        $request->validate([
            'name' => 'required',
            // 'description' => 'required',
            // 'parent_id' => 'required',
        ]);
        //dd($request->all());
        SystemModule::create($request->all());

        return redirect()->route('system-modules.index')
            ->with('success', 'System Modules created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemModule  $systemModule
     * @return \Illuminate\Http\Response
     */
    public function show(SystemModule $systemModule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SystemModule  $systemModule
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemModule $systemModule)
    {
        $parents = SystemModule::where('parent_id', '<=>')->orderBy('name')->get(['id','name']);
        return view('settings.system_modules.system_modules', ['systemModule' => $systemModule,'parents' => $parents]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SystemModule  $systemModule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemModule $systemModule)
    {
        $request->validate([
            'name' => 'required',
            // 'description' => 'required',
            // 'parent_id' => 'required',
        ]);

        $systemModule->update($request->all());

        return redirect()->route('system-modules.index')
            ->with('success', 'System Modules updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemModule  $systemModule
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemModule $systemModule)
    {
        try {
            return $systemModule->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
