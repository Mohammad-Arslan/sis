<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\SystemModule;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Requests\CreateRoleRequest;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::with('permissions')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                    <a class="btn btn-sm btn-success btn-icon waves-effect waves-light" href="'.route("roles.edit",  $row->id).'"><i class="mdi mdi-lead-pencil"></i></a>
                    <a class="btn btn-sm btn-danger btn-icon waves-effect delete-record" href="'.route("roles.destroy",  $row->id).'" data-table="roles-table"><i class="ri-delete-bin-5-line"></i></a>
                    ';
                    return $actionBtn;
                })
                ->addColumn('permissions', function($row){
                    $count = ($row->permissions->count());
                    return $count;
                })
                ->rawColumns(['action', 'permissions'])
                ->make(true);
        }
        return view('roles.index');

    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', [ 'permissions', $permissions]);
    }

    public function store(CreateRoleRequest $request)
    {
        try {
            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            if ($role->wasRecentlyCreated) {
                return redirect('roles')->with('success', 'Role is created!');
            }else{
                return redirect('roles')->withErrors($request)->withInput();
            }

        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function show(Role $role)
    {

    }

    public function edit(Role $role)
    {
        $role_permissions = $role->permissions()->get()->pluck('id')->toArray();
        // $permissions = Permission::all(['id', 'name', 'display_name' ,'description'])
        //     ->map(function ($permission) use ($role) {
        //         $permission->assigned = $role->permissions
        //             ->pluck('id')
        //             ->contains($permission->id);

        //         return $permission;
        //     });

            $system_modules = SystemModule::with('modules_permission')->orderBy('name')
            ->get();
            foreach ($system_modules as $key => $system_module) {

                $system_module->modules_permission->map(function ($permission) use ($role) {
                    $permission->assigned = $role->permissions->pluck('id')->contains($permission->id);
                    return $permission;
                });
            }

        return view('roles.edit', [
            'role'=> $role,
            'permissions'=> $system_modules,
            'role_permissions'=> $role_permissions,
        ]);
    }

    public function update(CreateRoleRequest $request,Role $role)
    {
        //dd($request->input('permissions'));
        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        if($role->save()){
            //if($request->input('permissions')){
                $role->syncPermissions($request->input('permissions') ?? []);
            //}
        }

        return redirect(route('roles.index'))->with('success', 'Role has been updated!');
    }

    public function destroy(Role $role)
    {
        try {
            return $role->delete();
            //return redirect('roles')->with('success', 'Role record has been deleted');

       } catch (QueryException $e) {
           print_r($e->errorInfo);
       }
    }
}
