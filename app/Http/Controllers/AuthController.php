<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function authRegister(Request $request) {

        if(\auth('sanctum')->check()) {
            return response(['message' => 'already signed in']);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(),[
            'name' => 'required|string',
            'family' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:5|confirmed'
        ]);

        if($validator->fails()) {
            return response(['message' => $validator->getMessageBag()->getMessages()]);
        }

        $user = User::create([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'family' => $request->input('family'),
            'password' => bcrypt($request->input('password')),
        ]);
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = ['user' => $user,'token' =>$token];
        return response($response,201);
    }

    public function authLogin(Request $request) {
        $validator = Validator::make($request->all(),[
            'email'=> 'required|email|string',
            'password' => 'required|string|min:5'
        ]);

        if($validator->fails()) {
            return response(['message' => $validator->getMessageBag()->getMessages()]);
        }

        $user = User::where('email',$request->input('email'))->first();
        if(!$user || !Hash::check($request->input('password'),$user->password)) {
            return response([
                'message' => 'wrong password or email'
            ],401);
        }
        $token = $user->createToken('myapp')->plainTextToken;
        $response = ['user' => $user, 'token' => $token];

        // includes remember_me_cookie for front end
        if($request->has('remember_me')) {
            setcookie('remember_me_cookie',$token, time()*60);
        };

        return response($response,201);
    }

    public function authLogout(Request $request) {
        auth()->user()->tokens()->delete();
        Cookie::forget('remember_me_cookie');
        return ['message' => 'loggedOut'];
    }
}
