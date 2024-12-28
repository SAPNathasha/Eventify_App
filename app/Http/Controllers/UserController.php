<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function register(Request $request)
    {
        try {

            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username', // Required, unique, max 255 chars
                'email' => 'required|email|max:255|unique:users,email',       // Required, valid email, unique, max 255 chars
                'password' => 'required|string|min:8',             // Required, min 8 chars, confirmation required
                'name' => 'required|string|max:255',                         // Required, max 255 chars
                'phone_number' => 'nullable|string|regex:/^[0-9+\-\(\)\s]*$/|max:15', // Optional, valid phone number, max 15 chars
                'gender' => 'nullable|string|in:male,female,other',          // Optional, must be one of: male, female, other
                'role' => 'nullable|string|in:subscriber,admin',             // Optional, must be subscriber or admin
            ]);



            $username = $validated['username'];
            $password = bcrypt($validated['password']);
            $name = $validated['name'];
            $email = $validated['email'];
            $phone_number = $validated['phone_number'] ?? null;
            $gender = $validated['gender'] ?? null;
            $role = $validated['role'] ?? 'subscriber';


            $user = User::create([
                'username' => $username,
                'password' => $password,
                'name' => $name,
                'email' => $email,
                'phone_number' => $phone_number,
                'gender' => $gender,
                'role' => $role
            ]);

            return response()->json([
                "status" => 200,
                "message" => "Registered Successfull!"
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request){
        try{
            $validated = $request->validate([
                'username' => 'required|string|max:255',
                'password' => 'required|string|min:8',
            ]);

            $username = $validated['username'];
            $password = $validated['password'];

            $user = User::where('username', $username)->first();
            if (!$user) {
                return response()->json([
                    "status" => 401,
                    "message" => "Invalid username or password"
                ]);
            }

            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    "status" => 401,
                    "message" => "Invalid username or password"
                ]);
            }
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                "status" => 200,
                "message" => "Login Successfull!",
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 500,
                'message' => 'Server error.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
