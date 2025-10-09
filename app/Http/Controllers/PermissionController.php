<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\SystemModule;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\CreatePermissionRequest;
use InvalidArgumentException;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::with('system_modules')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                    <a href="' . route("permissions.edit", $row->id) . '" class="btn btn-sm btn-success btn-icon waves-effect waves-light"><i class="mdi mdi-lead-pencil"></i></a>
                    <a href="' . route("permissions.destroy", $row->id) . '" class="btn btn-sm btn-danger btn-icon waves-effect  delete-record" data-table="permissions-table"><i class="ri-delete-bin-5-line"></i></a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('permissions.index');
    }

    public function create()
    {
        $modules = SystemModule::select('id', 'name')->get();

        return view('permissions.create', ['modules' => $modules]);
    }

    public function store(CreatePermissionRequest $request)
    {
        // dd($request->all());
        try {
            $permission = Permission::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'system_module_id' => $request->system_module_id,
            ]);

            if ($permission->wasRecentlyCreated) {
                return redirect('permissions')->with('success', 'Permission is created!');
            } else {
                return redirect('permissions')->withErrors($request)->withInput();
                //return back()->withErrors($request->errors());
            }
        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function show(Permission $permission)
    {
        //return view('permissions.show', ['permission' => $permission]);
    }

    public function edit($id)
    {
        $permission = Permission::where([
            'id' => $id,
        ])->first();
        $modules = SystemModule::select('id', 'name')->get();
        return view('permissions.edit', ['permission' => $permission, 'modules' => $modules]);
    }

    public function update(CreatePermissionRequest $request, Permission $permission)
    {
        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
            'system_module_id' => $request->system_module_id,
        ]);

        return redirect(route('permissions.index'))->with('success', 'Permission is updated!');
    }

    public function destroy(Permission $permission)
    {
        try {
            return $permission->delete();
            //return redirect(route('permissions.index'))->with('success', 'Permission record has been deleted');

        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
