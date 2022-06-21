<?php

use Illuminate\Http\Request;
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

Route::post('/resetPassword',[ForgetPasswordController::class,'sendEmail']);
Route::get('/resetPasswordLink',[ForgetPasswordController::class,function(){
    return response(['message' => 'send it to FRONT-END']);
}])->name('resetPassword');

//=========================== Protected Routes ============================

Route::group(['middleware' => ['auth:sanctum']],function() {
    Route::post('/products',[ProductController::class,'store']);
    Route::put('/products/{id}',[ProductController::class,'update']);
    Route::delete('/products/{id}',[ProductController::class,'destroy']);

    Route::post('/authlogout',[AuthController::class,'authLogout']);
});




