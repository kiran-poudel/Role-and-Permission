<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:View roles', only: ['index']),
            new Middleware('permission:Edit roles', only: ['edit']),
            new Middleware('permission:Create roles', only: ['create']),
            new Middleware('permission:Delete roles', only: ['destroy']),
        ];
    }

    //This method will show role page
    public function index()
    {
        $roles = Role::orderBy('name', 'asc')->paginate(10);
        return view("roles.list", [
            "roles" => $roles,
        ]);
    }

    //This method will create role page
    public function create()
    {

        $permissions = Permission::orderBy("name", "asc")->get();
        return view("roles.create", [
            "permissions" => $permissions
        ]);

    }

    //This method will insert role in db
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => "required|unique:roles|min:3",
        ]);
        if ($validator->passes()) {
            $role = Role::Create([
                "name" => $request->name
            ]);

            if (!empty($request->permission)) {
                foreach ($request->permission as $name) {
                    $role->givePermissionTo($name);
                }
            }
            return redirect()->route("roles.list")->with("success", "Role Added Successfully");
        } else {
            return redirect()->route("roles.create")->withInput()->withErrors($validator);
        }

    }

    //This method will show edit role page
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy("name", "asc")->get();

        return view("roles.edit", [
            "roles" => $role,
            "permissions" => $permissions,
            "hasPermissions" => $hasPermissions
        ]);

    }

    //This method will update role page
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "name" => "required|min:3",
        ]);
        if ($validator->passes()) {

            $role->name = $request->name;
            $role->save();

            if (!empty($request->permission)) {
                $role->syncPermissions($request->permission);
            } else {
                $role->syncPermissions([]);
            }
            return redirect()->route("roles.list")->with("success", "Role Updated Successfully");
        } else {
            return redirect()->route("roles.edit", $id)->withInput()->withErrors($validator);
        }

    }

    //This method will delete role
    public function destroy($id)
    {

        $role = Role::findOrFail($id);

        if ($role == null) {
            session()->flash("error", "Role Not Found");
            return response()->json([
                'status' => false

            ]);
        }
        $role->delete();
        session()->flash("success", "Role deleted successfully");
        return redirect()->route('roles.list');

    }

}
