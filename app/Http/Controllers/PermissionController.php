<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionUpdateValidator;
use App\Http\Requests\PermissionValidator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:View permission', only: ['index']),
            new Middleware('permission:Edit permission', only: ['edit']),
            new Middleware('permission:Create permission', only: ['create']),
            new Middleware('permission:Delete permission', only: ['destroy']),
        ];
    }

    // This method will show permission page
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'desc')->paginate(10);
        return view("permissions.list", [
            "permissions" => $permissions
        ]);
    }


    // This method will show create permission page
    public function create()
    {
        return view("permissions.create");
    }


    // This method will insert permission in db
    public function store(PermissionValidator $request)
    {

        Permission::Create([
            "name" => $request->name
        ]);
        return redirect()->route("permissions.list")->with("success", "Permission Added Successfully");
    }

    // This method will show edit permission page
    public function edit($id)
    {
        $permissions = Permission::findOrFail($id);
        return view("permissions.edit", [
            "permissions" => $permissions
        ]);
    }

    // This method will update permission
    public function update(Request $request, $id)
    {
        $permissions = Permission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "name" => "required|min:3"
        ]);

        if ($validator->passes()) {

            $permissions->name = $request->name;
            $permissions->save();

            return redirect()->route("permissions.list")->with("success", "Permission Updated Successfully");
        } else {
            return redirect()->route("permissions.edit", $id)->withInput()->withErrors($validator);
        }

    }


    // This method will delete permission
    public function destroy(Request $request)
    {

        $id = $request->id;

        $permissions = Permission::findOrFail($id);

        if ($permissions == null) {
            session()->flash("error", "Permission Not Found");
            return response()->json([
                'status' => false

            ]);
        }
        $permissions->delete();
        session()->flash("success", "Permission deleted successfully");
        return redirect()->route('permissions.list');
    }

    public function search(Request $request)
    {

        $search = $request->search;

        $permissions = Permission::where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->paginate(10);

        return view("permissions.list", compact("search", "permissions"));

    }
}
