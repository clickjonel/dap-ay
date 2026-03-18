<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginPage()
    {
        return Inertia::render('auth/login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Safety check: Ensure the relationship is loaded and exists
            if (!$user->accessLevels) {
                Auth::logout();
                abort(404, 'User has no assigned access level.');
            }

            return match ((int) $user->accessLevels->access_level) {
                1 => redirect()->intended('/dashboard/access-level-one'), // admin
                2 => redirect()->intended('/dashboard/access-level-two'),        // user
                3 => redirect()->intended('/dashboard/level-three'),      // pdoho
                
                default => abort(404, 'Access Level Not Recognized'),
            };
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our health portal records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

}
