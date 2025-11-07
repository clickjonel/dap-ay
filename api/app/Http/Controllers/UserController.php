<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function selection()
    {
        $users = User::get();

        return response()->json($users);
    }

    public function list(Request $request)
    {
        $keyword = $request->keyword ?? '';

        $query = User::query();
        $list = $query->when(isset($keyword), function($query) use ($keyword) {
                 $query->where('first_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('first_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('middle_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('last_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('nickname', 'LIKE', "%{$keyword}%");
                })
                ->simplePaginate(20);

        return response()->json($list);
    }
}
