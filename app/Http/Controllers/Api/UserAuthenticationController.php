<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\OtpRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserAuthenticationController extends Controller
{
    function register(AuthRequest $request)
    {   //validation - check validation pass - check if user exist

        //create user
        //be carful don't use $request->all() it 'll make your sytem unprotected
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number
        ]);

        $user->sendEmailVerificationNotification();

        return response(['successful registeration'], 200);
    }
    function login(AuthRequest $request)
    {
        //generate token 
        $user = User::where('email', $request->email)->first();
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $token = $user->createToken('newToken')->plainTextToken;
            return response(['message' => 'Successfuly login ', 'token' => $token]);
            //return redirect()->route('home');
        }


        return response(['message' => 'user not register before or check your email or password'], 200);
    }

    function byEmailverify(OtpRequest $request)
    {
        
        $user = User::where('email', $request->email)->first();
          //you don't need to send email if user already verified his/her email  
        if ($user->email_verified_at) return response(['your email is already verified'], 200);

        if (!$user) {
            return response(['message' => 'user not register to verify his email']);
        }
        $user->sendEmailVerificationNotification();
        return response(['email verification messsage succesfully sended check your inbox']);
    }
    //display otp page
    //sms 
    function vonageSms($phone, $otp)
    {
        $basic  = new \Vonage\Client\Credentials\Basic("00cea78d", "3DTQSptV4VY4kCBm");
        $client = new \Vonage\Client($basic);
        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS($phone, $phone, "Simplify code = {$otp} valid for 3 minutes")

        );
        $message = $response->current();

        return $message;
    }
    //send sms
    function sendOtp(OtpRequest $request)
    {

        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user) {
            return response(['message' => 'user with this phone number doesn\'t exist try with different phone ore register again']);
        }

        //you don't need to send SMS if user already verified his/her email  
        if ($user->email_verified_at) return response(['your email is already verified'], 200);

        $otp = rand(1000, 9000);
        $phone = $user->phone_number;

        $message = $this->vonageSms($phone, $otp); // actual message 

        if ($message->getStatus() == 0) {
            $user->update([
                'otp_sms_time' => Carbon::now(), //set time of SMS send
                'otp'=>$otp
            ]);
            $user->save();
            return response(['The message was sent successfully'], 200);
        } else {
            return response(['The message failed with status:'], $message->getStatus());
        }
        
 
    }

    //compare otp && verify email
    function compareOtp( $request, $user)
    {    
        //get the current time use carbon and the stored time in db subtract both 
        //if time was exceed 3 minuts ask user to resend 
        //else get user by phone 
        //update verifyied column to carbon time now 

        $sms_time =  carbon::parse($user->otp_sms_time); // parse function convert string to carbon 
        $currentTime = carbon::now(); 
        $durationInMinutes = $currentTime->diffInMinutes($sms_time); // time between send SMS and now must not exceed 3 minutes
        if(($user->otp == $request->otp) &&$durationInMinutes <=3){
            return true;
        }
       return false;
    }

    //compare otp && verifyEmail  
    function byPhoneVerify(OtpRequest $request){

        $user=User::where('phone_number',$request->phone_number)->first();
        if (!$user){
            return response(['message' => 'user with this phone number doesn\'t exist try with different phone or register again']);
        } 
        if(!($user->otp&&$user->otp_sms_time)){
            return response(['message' => 'user otp doesn\'t send to mobile please send sms again']);
        }
        if($user->email_verified_at){
            return response(['your email is already verified'], 200);//you don't need to send email if user already verified his/her email
        } 

        $status=$this->compareOtp($request,$user);

        if($status){
            $user->update([
               'email_verified_at'=>Carbon::now()
            ]);
            $user->save();
            return response(['message'=>'your email verified Successfully','otp'=>$user->otp]);
        }
        return response(['message'=>'failed verification your entered code doesn\'t match sms sended code or 3 minutes out try verify by phone again']);
    }
 //compare otp && resetPassword
 function byPhoneReset(OtpRequest $request)
 {
     
     $user=User::where('phone_number',$request->phone_number)->first();
     if (!$user){
         return response(['message' => 'user with this phone number doesn\'t exist try with different phone or register again']);
     } 
     if(!($user->otp&&$user->otp_sms_time)){
         return response(['message' => 'user otp doesn\'t send to mobile please send sms again']);
     }

     $status=$this->compareOtp($request,$user); //compare otp
     
     $user->update([
        'otp_status'=>$status
     ]);
     $user->save();
     
     if($status){  
         return response(['message'=>'code is correct please enter your new password','otp'=>$user->otp]);
     }
     return response(['message'=>'code is not correct please try again']);
 }

    function byPhoneResetPasswordForm(OtpRequest $request){
    $user=User::where('otp',$request->otp)->first();
     if (!$user){
         return response(['message' => 'user with this code doesn\'t exist resend verification code or may be your account accidentaly deleted']);
     } 
     if(!($user->otp&&$user->otp_sms_time)){
         return response(['message' => 'user otp doesn\'t send to mobile please send sms again']);
     }

        if(($request->phone==$request->confirm_phone)&&$user->otp_status){
            $user->update($request->validated()+['otp_status'=>0]);
            $user->save();
           return response(['message'=>'successfully reset your password'],200); 
        }
        return response(['message'=>'failed reset your password'],401);
    }
}
