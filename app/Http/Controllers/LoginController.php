<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function authenticated(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(
                ["msgEN" => "Login fail.", "msgPL" => "Błąd logowania."],
                404
            );
        }

        $token = $user->createToken('cookbook')->plainTextToken;


        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }
}