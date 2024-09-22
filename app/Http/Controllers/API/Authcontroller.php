<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class Authcontroller extends Controller
{
    public function register(Request $request)
    {
        $validateuser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,',
                'password' => 'required',
            ]
        );
        if ($validateuser->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'validationerror',
                'error' => $validateuser->errors()
            ]);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        if ($user) {
            return response()->json([
                'status' => 'true ',
                'message' => 'user created successfully',
                'user' => $user,
            ]);
        }
    }
    public function login(Request $request)
    {
        $validateuser = validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );
        if ($validateuser->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'validation in login failed',
                'error' => $validateuser->errors()
            ]);
        }



        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            return response()->json([
                'status' => 'true',
                'message' => 'user logged in',
                'token' => $user->createToken('api token')->plainTextToken,
                'token_type'=>'bearer'
                // 'token'=>$user->createToken('api token')->plainTextToken,
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'email and password does not matched',
            ]);
        }
    }
    public function logout(Request $request){
        // $user=Auth::user();
        $user=$request->user();
        $user->tokens()->delete();
        return response()->json([
            'status' => 'true',
            'message' =>'you logged out successfully',
            'user' => $user,

        ]);
    }
}
