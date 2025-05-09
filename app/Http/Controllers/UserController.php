<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Password;
use illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {

            $request->validate([
                "name" => "required|string|max:100",
                "user_name" => "required|string|max:255|unique:users",
                "phone" => ["required", "max:13", "regex:/^(\+62|62)?[\s-]?0?8[1-9]{1}\d{1}[\s-]?\d{4}[\s-]?\d{2,5}$/"],
                "email" => "required|email|unique:users",
                "password" => ['required', 'string', 'min:6', 'regex:/^(?=.*[A-Z])(?=.*\d).{6,}$/'],
            ]);

            $user = User::create([
                "name" => $request->name,
                "user_name" => $request->user_name,
                "phone" => $request->phone,
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);

            return response()->json(["message" => "registrasi berhasil", "data" => $user], 201);
        } catch (ValidationException $e) {
            return response()->json(["error" => $e->validator->errors()], 422);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function allUser()
    {
        try {
            $users = User::all();
            return response()->json(["data" => $users], 200);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }

    public function user($id)
    {
        try{
            $user = User::findOrfail($id);
    
            return response()->json(["Data" => $user]);

        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()]);
        }

    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);


            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'user_name' => 'sometimes|required|string|max:255|unique:users,user_name,' . $user->id,
                'phone' => ['sometimes', 'required', 'regex:/^(\+62|62)?[\s-]?0?8[1-9]{1}\d{1}[\s-]?\d{4}[\s-]?\d{2,5}$/'],
                // 'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
                // 'password' => ['sometimes', 'required', 'string', 'min:6', 'regex:/^(?=.*[A-Z])(?=.*\d).{6,}$/'],
            ]);

            $user->update([
                "name" => $request->name ?? $user->name,
                "user_name" => $request->user_name ?? $user->User_name,
                "phone" => $request->phone ?? $user->phone,
                // "email" => $request->email ?? $user->email,
                // "password" => $request->password ? Hash::make($request->password) : $user->password,
            ]);

            return response()->json(["message" => "Data berhasil diperbaharui", "data" => $user]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }

    public function forgotpassword(Request $request)
    {
        try {
            $request->validate(["email" => "required|email|exist:users,email"]);
            $status = Password::sendResetLink($request->only("email"));
            return response()->json(["message" => __($status)]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                "email" => "required|email",
                "password" => "required|string",
            ]);

            $user = User::where("email", $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(["message" => "Email atau password salah"], 401);
            }

            $token = $user->createToken("auth_token")->plainTextToken;


            return response()->json(["message" => "Login berhasil", "user" => $user, "token" => $token]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(["message" => "Logout berhasil"]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->delete();
            
            return response()->json(["message" => "Customer berhasil dihapus"], 201);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }
}
