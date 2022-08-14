<?php

namespace App\Http\Controllers;

use App\Jobs\ResetPasswordEmailJob;
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
               'error' => $validator->errors()
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
       if(Mail::failures() != 0) {
           return response('email has been send succesfuly');
       }
       return "Oops! there where some errors";
   }

   public function resetPassword(Request $request) {
        $request->query('token');// check it with the token  stored previously on database
       // POST Email
       // POST password
       // POST password_confirmation
       // check if the email is the same with the database
       // after that => update the user password database
       // and we are done :)
   }
}
