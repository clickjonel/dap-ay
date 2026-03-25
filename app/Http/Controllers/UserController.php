<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function accountSettings()
    {
        return Inertia::render('user/accountSettings',[

        ]);
    }

    public function updateAccountSettings(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->user()->id,
        ]);

        $request->user()->update($validated);

        return back();
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required',
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return back();
    }
}
