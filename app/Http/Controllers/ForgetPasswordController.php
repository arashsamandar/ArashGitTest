<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgetPasswordController extends Controller
{
   public function sendEmail(Request $request) {
       $validator = Validator::make($request->all(),[
          'email' => 'required|email|exists:users'
       ]);
       if($validator->fails()){
           return response([
               'error' => $validator->getMessageBag()->getMessages()
           ]);
       }
       $token = Str::random(64);
       $time = Carbon::now();
       $email = $request->input('email');
       DB::table('password_resets')->insert([
          'email' => $email,
          'created_at' => $time,
          'token' => $token
       ]);
       Mail::send('email.forgetPassword',['token' => $token],function($message) use($request,$email){
           $message->to($email);
           $message->subject('Reset Password');
       });
   }
}
