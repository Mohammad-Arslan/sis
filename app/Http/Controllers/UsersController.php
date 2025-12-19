<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Laratrust\Helper;
use App\Models\Employee;
use App\Models\Permission;
use App\Models\SystemModule;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PhpParser\Node\Stmt\Foreach_;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $data = User::with(['roles','permissions','employee'])->get();
        //dd($data[5]->employee->user_id);
        if ($request->ajax()) {
            $data = User::with(['roles','permissions','employee'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if (isset($row->employee->id)) {
                        $actionBtn = '
                        <button type="button" class="btn btn-sm btn-info btn-icon waves-effect waves-light show-modal" data-url="' . route("employees.show", $row->employee->id) . '" data-target="#profileEmpModal"><i class="mdi mdi-account"></i></button>
                    ';
                    } else {
                        $actionBtn = '';
                    }

                    return $actionBtn;
                })
                ->addColumn('roles', function ($row) {
                    $count = ($row->roles->count());
                    return $count;
                })
                ->addColumn('permissions', function ($row) {
                    $count = ($row->permissions->count());
                    return $count;
                })
                ->rawColumns(['action', 'roles', 'permissions'])
                ->make(true);
        }
        return view('employees.index');
    }
    // roles permissions assignments
    public function userRolesPermissionList(Request $request)
    {

        $modelsKeys = array_keys(Config::get('laratrust.user_models'));
        $modelKey = $request->get('model') ?? $modelsKeys[0] ?? null;
        //dd(User::with(['roles','permissions'])->get()->toArray());
        if ($request->ajax()) {
            $data = User::with(['roles','permissions'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <a class="btn btn-sm btn-success btn-icon waves-effect waves-light" href="' . route("edit-with-role-permissions", ['id' => $row->id]) . '"><i class="mdi mdi-lead-pencil"></i></a>
                    ';

                    return $actionBtn;
                })
                ->addColumn('roles', function ($row) {
                    $count = ($row->roles->count());
                    return $count;
                })
                ->addColumn('permissions', function ($row) {
                    $count = ($row->permissions->count());
                    return $count;
                })
                ->rawColumns(['action', 'roles', 'permissions'])
                ->make(true);
        }

        return view('role_permissions_assignment.index', [
            'models' => $modelsKeys,
            'modelKey' => $modelKey,
        ]);
    }

    public function editUserRolesPermissions(Request $request, $id)
    {

        $user = User::query()
            ->with(['roles:id,name', 'permissions:id,name'])
            ->findOrFail($id);
        $roles = Role::orderBy('name')->get(['id', 'name', 'display_name' ,'description'])
            ->map(function ($role) use ($user) {
                $role->assigned = $user->roles
                ->pluck('id')
                    ->contains($role->id);
                $role->isRemovable = Helper::roleIsRemovable($role);

                return $role;
            });

        //['id', 'name', 'display_name' ,'description']
      /*   $permissions = Permission::with('system_modules')->orderBy('system_module_id')
         ->get()
         ->map(function ($permission) use ($user) {
             $permission->assigned = $user->permissions
                 ->pluck('id')
                 ->contains($permission->id);

             return $permission;
         });

*/
        $system_modules = SystemModule::where('parent_id', '<=>')->with('modules_permission')->orderBy('name')
        ->get();

        foreach ($system_modules as $key => $system_module) {
            $system_module->modules_permission->map(function ($permission) use ($user) {
                $permission->assigned = $user->permissions->pluck('id')->contains($permission->id);
                return $permission;
            });
        }





        //dd($system_modules->toArray());
        $data['roles'] = $roles;
        $data['permissions'] = $system_modules;
        $data['user'] = $user;

        return view('role_permissions_assignment.edit', $data);
    }


    public function updateUserRolesPermissions(Request $request, $id)
    {
        $modelKey = 'users';
        $userModel = Config::get('laratrust.user_models')[$modelKey] ?? null;

        if (! $userModel) {
        //'Model was not specified in the request';
        //return redirect()->back()->with('error','Unfortunately not able to update the role assignment');
        }

        $user = $userModel::findOrFail($id);
        $user->syncRoles($request->get('roles') ?? []);
        $user->syncPermissions($request->get('permissions') ?? []);

        return redirect()->back()->with('success', 'Your details for the user have been successfully updated!');
    }

    public function editUserPassword($id)
    {
        if ($id != null) {
            $user_id = $id;
        }
        // $user_record = null;
        // $user_record = User::find($id);
        // if(isset($user_record) && $user_record[0] != null);
        return view('employees.employee_password_modal', compact('user_id'));
    }

    public function credential_rules(array $data)
    {
        $messages = [
            //'current-password.required' => 'Please enter current password',
            'password.required' => 'Please enter password',
            'password.min' => 'Password must be at least 6 characters.',
            'password.max' => 'Password may not be greater than 16 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'confirm_password.required' => 'Please confirm your password',
            'confirm_password.same' => 'Confirm password must match the password',
        ];

        $validator = Validator::make($data, [
            //'current-password' => 'required',
            'password' => [
                'required',
                'min:6',
                'max:16',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
            'confirm_password' => 'required|same:password',
        ], $messages);

        return $validator;
    }

    public function updateUserPassword(Request $request)
    {
        //dd($request->all());
        $request_data = $request->All();
        $validator = $this->credential_rules($request_data);
        if ($validator->fails()) {
            return response()->json(array('error' => $validator->getMessageBag()->toArray()), 400);
        } else {
            $obj_user = User::find($request->id);
            $obj_user->password = Hash::make($request_data['password']);
            $obj_user->save();
            return response()->json(['success' => "User password have been successfully updated!"], 200);
        }
    }


    //Login user password change functionalities.

    public function getUserPassword()
    {
        return view('employees.change_password_modal');
    }

    public function credential_validation_rules(array $data)
    {
        $messages = [
            'current_password.required' => 'Please enter current password',
            'new_password.required' => 'Please enter password',
        ];

        $validator = Validator::make($data, [
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ], $messages);

        return $validator;
    }

    public function changeUserPassword(Request $request)
    {
        //dd($request->all());
        $request_data = $request->All();
        $validator = $this->credential_validation_rules($request_data);
        if ($validator->fails()) {
            return response()->json(array('error' => $validator->getMessageBag()->toArray()), 400);
        } else {
            $current_password = Auth::User()->password;
            if (Hash::check($request_data['current_password'], $current_password)) {
                $user_id = Auth::User()->id;
                $obj_user = User::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);
                $obj_user->save();
                return response()->json(['success' => "Your password have been successfully updated!"], 200);
            } else {
                $error = array('current_password' => 'Please enter correct current password');
                return response()->json(array('error' => $error), 400);
            }
        }
    }
}
