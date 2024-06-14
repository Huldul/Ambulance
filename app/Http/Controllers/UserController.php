<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'phone_number' => 'sometimes|unique:users,phone_number,' . $user->id,
            'full_name' => 'sometimes',
            'date_of_birth' => 'sometimes|date',
            'residence' => 'sometimes',
        ]);

        $user->update($request->only(['phone_number', 'full_name', 'date_of_birth', 'residence']));

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
