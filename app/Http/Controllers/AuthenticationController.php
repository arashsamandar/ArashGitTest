<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Validator;
use http\Env\Response;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(Request $request) {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
           'name' => $validator['name'],
           'family' =>  $validator['family'],
           'email' => $validator['email'],
           'password' => bcrypt($validator['password'])
        ]);

        return response()->json(
            ['token' => $user->createToken('tokens')->plainTextToken]);
    }

    public function login(Request $request) {
        $validator = $request->validate([
           'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        if(!Auth::attempt($request->only('email','password'))) {
            return response()->json(['credentials does not match',], 200);
        }

        return response()->json(['token' => \auth()->user()->createToken('API Token')->plainTextToken], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json('null',200);
    }

    public function sanctumTest(Request $request) {
        if($request->user()) {
            return response()->json(\request()->user());
        } else {
            return response()->json('guest');
        }
    }

}
