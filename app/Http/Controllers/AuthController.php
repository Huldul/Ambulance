<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'iin' => 'required|unique:users',
            'phone_number' => 'required|unique:users',
            'full_name' => 'required',
            'date_of_birth' => 'required|date',
            'residence' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'iin' => $request->iin,
            'phone_number' => $request->phone_number,
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'residence' => $request->residence,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'iin' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['iin' => $request->iin, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->accessToken;

            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function sendSms(Request $request)
    {
        // Здесь можно использовать любой сервис для отправки SMS.
        // Для теста просто возвращаем код "1234".
        return response()->json(['sms_code' => '1234']);
    }

    public function verifySms(Request $request)
    {
        $request->validate([
            'sms_code' => 'required',
        ]);

        if ($request->sms_code == '1234') {
            return response()->json(['message' => 'SMS verified successfully'], 200);
        } else {
            return response()->json(['error' => 'Invalid SMS code'], 400);
        }
    }
}
