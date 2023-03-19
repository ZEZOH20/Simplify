
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthenticationController;
use App\Http\Requests\API\EmailVerificationRequest as CustomEmailVerificationRequest;

use Illuminate\Foundation\Auth\EmailVerificationRequest; // custom EmailVerificationRequest rather than default that is work with unAutherize useruse Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

//*********Authentication**********
Route::group(['prefix'=>'login'],function(){
    Route::post('/',[UserAuthenticationController::class,'login'])->name('login');
    Route::get('/',function(){echo 'login page';})->name('view.login');
});
Route::post('register',[UserAuthenticationController::class,'register'])->name('register');
//-------

//********Email Verification********* 

//verified middleware redirect if user not verified
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth:sanctum')->name('verification.notice');  

//handle requests generated when the user clicks the email verification link that was emailed
Route::get('/email/verify/{id}/{hash}', function ( CustomEmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->name('verification.verify'); 

//resend a verification link if the user accidentally loses the first verification link
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware('auth:sanctum')->name('verification.send');
//---------
//Email verification OTP
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
//***********Reset Password*********
//view
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

//Form request 
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
 
    $status = Password::sendResetLink(
        $request->only('email')
    );
 
    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');
// clicks the reset password link email view
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

//rest password 
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
 
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
 
            $user->save();
 
            event(new PasswordReset($user));
        }
    );
 
    return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');
//---------