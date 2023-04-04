
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthenticationController;
use App\Http\Requests\API\EmailVerificationRequest as CustomEmailVerificationRequest;

use Illuminate\Foundation\Auth\EmailVerificationRequest; // custom EmailVerificationRequest rather than default that is work with unAutherize useruse Illuminate\Support\Facades\Password;


//*********Authentication**********
Route::group(['prefix'=>'login'],function(){
    Route::post('/',[UserAuthenticationController::class,'login'])->name('login');
    Route::get('/',function(){echo 'login page';}); //login
   
});
Route::post('register',[UserAuthenticationController::class,'register'])->name('register');
Route::post('/logout',[UserAuthenticationController::class,'logout'])->middleware('auth:sanctum'); //++++++++++++
//-------

//********Email Verification********* 

//verified middleware redirect if user not verified
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth:sanctum')->name('verification.notice');  

//handle requests generated when the user clicks the email verification link that was emailed
Route::get('/email/verify/{id}/{hash}', function ( CustomEmailVerificationRequest $request) {
    $request->fulfill();
    return  response(['message'=>'User\'s email has been verified successfully',
                      'redirect URL' => 'http://127.0.0.1:8000/api/home'
       ],200);
    // return redirect('/api/home');
})->name('verification.verify'); 

//resend a verification link if the user accidentally loses the first verification link
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware('auth:sanctum')->name('verification.send');
//---------
//Email verification OTP & password reset
Route::group(["prefix"=>'/byEmail'],function(){
    Route::post('/verify',[UserAuthenticationController::class,'byEmailverify'])->name('verifyByEmail');

});
Route::group(["prefix"=>'/byPhone'],function(){
    Route::post('/',[UserAuthenticationController::class,'sendOtp'])->name('sendOtp');
    Route::post('/verify',[UserAuthenticationController::class,'byPhoneVerify'])->name('compareOtp');
    Route::group(["prefix"=>'/reset'],function(){
        Route::post('/',[UserAuthenticationController::class,'byPhoneReset'])->name('compareOtp');
        Route::post('/form',[UserAuthenticationController::class,'byPhoneResetPasswordForm'])->name('byPhoneResetPasswordForm');
        Route::get('/',function(){
            echo 'resetPasswordOtpView';
        });
    });
    
});
//Email verification OTP

//??????????????????????????????????????????????????????????? under work
