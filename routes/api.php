<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgetPasswordController;

Route::get('/products',[ProductController::class,'index']);

Route::get('/products/{id}',[ProductController::class,'show']);

Route::get('/products/search/{name}',[ProductController::class,'search']);

//=============================== Public Routes ============================

Route::post('/authregister',[AuthController::class,'authRegister']);
Route::post('/authlogin',[AuthController::class,'authLogin']);

Route::post('/sendmail',[ForgetPasswordController::class,'sendEmail']);

//=========================== Protected Routes ============================

Route::group(['middleware' => ['auth:sanctum']],function() {
    Route::put('/products/{id}',[ProductController::class,'update']);
    Route::post('/products',[ProductController::class,'store']);
    Route::delete('/products/{id}',[ProductController::class,'destroy']);
    Route::post('/authlogout',[AuthController::class,'authLogout']);
});

Route::get('/resetPasswordLink',[ForgetPasswordController::class,'resetPassword'])->name('resetPassword');


