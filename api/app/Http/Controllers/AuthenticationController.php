<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(Request $request):JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);


        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])){
            $user = $request->user()->load(['teams.barangays']);

            $token = $user->createToken($user->id)->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
                'status' => true,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid Credentials, Please Try Again With Correct Credentials',
        ], 401);


    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();
            
            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to logout: ' . $e->getMessage()
            ], 500);
        }
    }
    
}
