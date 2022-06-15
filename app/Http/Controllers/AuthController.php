<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function authRegister(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'family' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:5|confirmed'
        ]);
        $user = User::create([
           'name' => $fields['name'],
           'family' => $fields['family'],
           'email' => $fields['email'],
           'password' => bcrypt($fields['password']),
        ]);
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = ['user' => $user,'token' =>$token];
        return response($response,201);
    }

    public function authLogin(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:5'
        ]) ;

        $user = User::where('email',$fields['email'])->first();
        if(!$user || !Hash::check($fields['password'],$user->password)) {
            return response([
                'message' => 'wrong password or email'
            ],401);
        }
        $token = $user->createToken('myapp')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response($response,201);
    }

    public function authLogout(Request $request) {
        auth()->user()->tokens()->delete();
        return ['message' => 'loggedOut'];
    }
}
