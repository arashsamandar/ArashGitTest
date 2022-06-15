<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
//Route::group(['middleware' => ['auth:sanctum']],function() {
//   Route::get('/profile',function () {
//       return auth()->user();
//   });
//   Route::post('/signout',[AuthenticationController::class,'logout']);
//});
//
//Route::post('/asr',[\App\Http\Controllers\AuthenticationController::class,'asr']);
//Route::post('/me', [\App\Http\Controllers\AuthenticationController::class, 'me'])->middleware('auth:sanctum');

//========================= Testing Product ===========================

Route::get('/products',[ProductController::class,'index']);

Route::get('/products/{id}',[ProductController::class,'show']);

Route::get('/products/search/{name}',[ProductController::class,'search']);

//=============================== Public Routes ============================

Route::post('/register',[AuthController::class,'authRegister']);

Route::post('/login',[AuthController::class,'authLogin']);

//=========================== Protected Routes ============================

Route::group(['middleware' => ['auth:sanctum']],function() {
    Route::post('/products',[ProductController::class,'store']);
    Route::put('/products/{id}',[ProductController::class,'update']);
    Route::delete('/products/{id}',[ProductController::class,'destroy']);

    Route::post('/authlogout',[AuthController::class,'authLogout']);

    Route::post('/logout',[\App\Http\Controllers\AuthenticationController::class,'logout']);
    Route::post('/check',[\App\Http\Controllers\AuthenticationController::class,'sanctumTest']);
});




