<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Hash;
class UserController extends Controller
    
{   //Register a new user
    public function register(Request $request)
    {
        try {
            // Validate data
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username', // Required, unique, max 255 chars
                'email' => 'required|email|max:255|unique:users,email',       // Required, valid email, unique, max 255 chars
                'password' => 'required|string|min:8',             // Required, min 8 chars, confirmation required
                'name' => 'required|string|max:255',                         // Required, max 255 chars
                'phone_number' => 'nullable|string|regex:/^[0-9+\-\(\)\s]*$/|max:15', // Optional, valid phone number, max 15 chars
                'role' => 'nullable|string|in:subscriber,admin',             // Optional, must be subscriber or admin
            ]);


            // Assign validated data to variables
            $username = $validated['username'];
            $password = bcrypt($validated['password']);
            $name = $validated['name'];
            $email = $validated['email'];
            $phone_number = $validated['phone_number'] ?? null;

            // Create a new user in database
            $user = User::create([
                'username' => $username,
                'password' => $password,
                'name' => $name,
                'email' => $email,
                'phone_number' => $phone_number,
                'role' => "subscriber"
            ]);
            
            // Return a success response
            return response()->json([
                "status" => 200,
                "message" => "Registered Successfull!"
            ]);
        } catch (\Exception $e) {
            // return errors
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // user login
    public function login(Request $request){
        try{
            // Validate data
            $validated = $request->validate([
                'username' => 'required|string|max:255',
                'password' => 'required|string|min:8',
            ]);

            $username = $validated['username'];
            $password = $validated['password'];
            // find users by username
            $user = User::where('username', $username)->first();
            if (!$user) {
                return response()->json([
                    "status" => 401,
                    "message" => "Invalid username or password"
                ]);
            }
            // verify password
            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    "status" => 401,
                    "message" => "Invalid username or password"
                ],401);
            }
            // generate authentication tokan to user
            $token = $user->createToken('auth_token')->plainTextToken;
            // return success authentication token
            return response()->json([
                "status" => 200,
                "message" => "Login Successfull!",
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],401);
            
        } catch (\Exception $e) {
            // return errors
            return response()->json([
                'status' => 500,
                'message' => 'Server error.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
