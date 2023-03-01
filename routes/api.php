<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthenticationController;
use App\Http\Controllers\Api\CustomEmailVerificationRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest; // custom EmailVerificationRequest rather than default that is work with unAutherize user
//use App\Classes\CustomValidation;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Authentication sanctum
Route::group(["prefex"=>'login'],function(){
    Route::post('/',[UserAuthenticationController::class,'login'])->name('login');
    Route::get('/',function(){echo 'login page';})->name('login-view');
});
Route::post('register',[UserAuthenticationController::class,'register'])->name('register');


// Email Verification 

//verified middleware redirect if user not verified
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth:sanctum')->name('verification.notice');  

//handle requests generated when the user clicks the email verification link that was emailed
Route::get('/email/verify/{id}/{hash}', function ( EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->name('verification.verify'); 

//resend a verification link if the user accidentally loses the first verification link
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware('auth:sanctum')->name('verification.send');
//---------
Route::group(['middleware'=>['auth:sanctum','verified']],function(){
    Route::post('test',function(){
        echo 'email verified';
    });
});