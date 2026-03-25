<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function getUsers(Request $request)
    {
        $users = User::query()
                    ->with(['accessLevels.province'])
                    ->when($request->search, function ($query, $search) {
                        $query->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orderBy('id', 'desc')
                    ->paginate(10)
                    ->withQueryString();

        return Inertia::render('user/users', [
            'users' => $users,
        ]);
    }

    public function resetPassword(User $user)
    {
        $user->update([
            'password' => '12345',
        ]);

        return back();
    }

}
