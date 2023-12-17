<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ], 401);
        }

        return response()->json([
            "status" => true,
            'msg' => 'Logged in successfully',
            "token" => $user->createToken($request->phone)->plainTextToken
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'msg' => 'Logged out successfully'
        ], 200);
    }

    public function registerUser(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    "name" => "required",
                    "password" => "required|min:8",
                    "phone" => "required|unique:users",
                ],
                [
                    "property.required" => "No property ID supplied",
                    "user.required" => "No user ID supplied",
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "msg" => "Registering user failed. " . join(". ", $validator->errors()->all()),
                ], 400);
            }

            DB::table("users")->insert([
                "name" => $request->name,
                "phone" => $request->phone,
                "usertype" => "user",
                "password" => Hash::make($request->password),
                "createdate" => date('Y-m-d'),
                "createuser" => $request->name,
            ]);

            return response()->json([
                "status" => true,
                "msg" => "Registered successfully",
            ], 200);
        } catch (\Exception $e) {
            Log::error("Failed to register user: " . $e->getMessage());
            return response()->json([
                "status" => false,
                "msg" => "Registering user failed. An internal error occured",
            ], 500);
        }
    }
}
