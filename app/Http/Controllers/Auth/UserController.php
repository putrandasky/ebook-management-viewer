<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            return UserResource::collection(User::paginate(10));
        }

        return response()->json(["message" => "Forbidden"], 403);
    }
    public function create(Request $request)
    {

        $rules = [
            'name' => 'required|string|min:6|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ];

        $this->validate($request, $rules);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'is_admin' => 1,
            'password' => Hash::make($request['password']),
        ]);
    }
    public function show(User $user)
    {
        if (Auth::user()->isAdmin()) {
            return new UserResource($user);
        }

        return response()->json(["message" => "Forbidden"], 403);
    }
    public function delete($user_id)
    {
        if (Auth::user()->isAdmin()) {
            $user = User::find($user_id);
            $user->delete();
            return response()->json(["Status" => "SUCCESS", "message" => "User Deleted"], 200);

        }

        return response()->json(["message" => "Forbidden"], 403);
    }
}
