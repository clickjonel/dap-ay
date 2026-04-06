<?php

namespace App\Http\Controllers;

use App\Models\Province;
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
        $user = $request->user();
        $users = User::query()
                    ->with(['accessLevels.province'])
                    ->when($request->search, function ($query, $search) {
                        $query->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->when($user->accessLevels->access_level === 3, function ($query) use ($user) {
                        $query->whereHas('accessLevels', function ($query) use ($user) {
                            $query->where('pdoho_access_id', $user->accessLevels->pdoho_access_id);
                        });
                    })
                    ->orderBy('id', 'desc')
                    ->paginate(10)
                    ->withQueryString();

        return Inertia::render('user/users', [
            'users' => $users,
            'provinces' => Province::all(['id', 'name']),
        ]);
    }

    public function resetPassword(User $user)
    {
        $user->update([
            'password' => '12345',
        ]);

        return back();
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'province_id' => 'required|exists:provinces,id',
        ]);

        $userCreated = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => '12345',
        ]);
        $userCreated->accessLevels()->create([
            'pdoho_access_id' => $validated['province_id'],
            'access_level' => 2,
        ]);

        return back();

    }

    public function userProfile(Request $request)
    {
        $user = $request->user();
        return Inertia::render('user/userProfile', [
            'user' => $user,
        ]);
    }

    public function updateUserProfile(Request $request)
    {
        $user = $request->user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back();
    }

}
