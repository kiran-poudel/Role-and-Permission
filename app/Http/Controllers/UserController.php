<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:View user', only: ['index']),
            new Middleware('permission:Edit user', only: ['edit']),
            new Middleware('permission:Create user', only: ['create']),
            new Middleware('permission:Delete user', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view("users.list", [
            "users" => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'asc')->get();

        return view("users.create", [
            "roles" => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|min:3",
            "email" => "required|email",
            "password" => "required|string|min:8|same:confirm_password",
            "confirm_password" => "required"
        ]);
        if ($validator->fails()) {

            return redirect()->route("users.create")->withInput()->withErrors($validator);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $user->syncRoles($request->role);

        return redirect()->route("users.list")->with("success", "User added Successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        $roles = Role::orderBy('name', 'asc')->get();

        $hasRoles = $user->roles->pluck('id');

        return view(
            "users.edit",
            [
                "user" => $user,
                "roles" => $roles,
                "hasRoles" => $hasRoles
            ]

        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            "name" => "required|min:3",
            "email" => "required|email",
        ]);
        if ($validator->fails()) {

            return redirect()->route("users.edit", $id)->withInput()->withErrors($validator);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $user->syncRoles($request->role);

        return redirect()->route("users.list")->with("success", "User Updated Successfully");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user == null) {
            session()->flash("error", "User Not Found");
            return response()->json([
                'status' => false

            ]);
        }
        $user->delete();
        session()->flash("success", "User deleted successfully");
        return redirect()->route('users.list');
    }
}
