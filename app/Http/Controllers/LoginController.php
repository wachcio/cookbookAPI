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
            // 'X-CSRF-TOKEN' => csrf_token()
        ];

        return response($response, 201);
        // if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
        //     $authenticated_user = \Auth::user();
        //     $user = User::find($authenticated_user->id);
        //     dd($user->createToken('cookbook')->accessToken);
        // }

        // dd('here');
    }
}