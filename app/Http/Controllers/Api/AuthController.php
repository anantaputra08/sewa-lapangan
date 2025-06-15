<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            // Mengembalikan respons error yang lebih sesuai untuk API
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
                'data' => null
            ], 401); // 401 Unauthorized
        }

        // Buat token untuk user
        $token = $user->createToken('api-token')->plainTextToken;

        // Sesuaikan struktur respons dengan yang diharapkan oleh Android (LoginResponse)
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }

    /**
     * Handle a registration request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        Log::info('Register attempt started.', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Registration validation failed.', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation Errors',
                'data' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => 'user', // Default role
            ]);

            Log::info('User registered successfully.', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'user' => $user
            ], 201); // 201 Created
        } catch (\Exception $e) {
            Log::critical('User registration failed.', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again later.',
                'data' => null
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Handle a logout request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan untuk otentikasi
        $request->user()->currentAccessToken()->delete();

        // Sesuaikan struktur respons dengan yang diharapkan oleh Android (LogoutResponse)
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }
}
