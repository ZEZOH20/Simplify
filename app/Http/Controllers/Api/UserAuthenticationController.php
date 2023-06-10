<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\OtpRequest;
use App\Models\Student;
use App\Models\Term;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserAuthenticationController extends Controller
{
    use \App\Classes\CustomResponse;

    function register(AuthRequest $request)
    {   //validation - check validation pass - check if user exist

        //create user
        //be carful don't use $request->all() it 'll make your sytem unprotected

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
        ]);
        $user->save();

        $user->sendEmailVerificationNotification();


        $student = new Student([
            'collage_id' => $request->collage_id,
            'gender' => $request->gender,
        ]);


        $user->student()->save($student);
        $term = new Term([
            'student_id' => $student->id
        ]);
        $user->student->term()->save($term);
        return $this->success(
            'successful registeration'
        );
    }
    function login(AuthRequest $request)
    {
        //generate token 
        $user = User::where('email', $request->email)->first();
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $token = $user->createToken('newToken')->plainTextToken;
            return response([
                'message' => 'Successfuly login ', 'token' => $token,
                'isAdmin' => (auth()->user()->type == 'admin') ? true : false
            ], 200);
            //return redirect()->route('home');
        }

        return $this->success('user not register before or check your email or password');
    }


    function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request...
        $request->user()->currentAccessToken()->delete();
        return $this->success('user successfully logout');
    }


    function byEmailverify(OtpRequest $request)
    {

        $user = User::where('email', $request->email)->first();
        //you don't need to send email if user already verified his/her email  
        if ($user->email_verified_at) return $this->failure('your email is already verified');
        if (!$user) {
            return $this->failure('user not register to verify his email');
        }
        $user->sendEmailVerificationNotification();
        return $this->success('email verification messsage succesfully sended check your inbox');
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
    // ?????????? add to here 
    //send sms
    function sendOtp(OtpRequest $request)
    {

        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user) {
            return $this->failure('user with this phone number doesn\'t exist try with different phone ore register again');
        }

        //you don't need to send SMS if user already verified his/her email  
        if ($user->email_verified_at) return $this->success('your email is already verified');

        $otp = rand(1000, 9000);
        $phone = $user->phone_number;

        $message = $this->vonageSms($phone, $otp); // actual message 

        if ($message->getStatus() == 0) {
            $user->update([
                'otp_sms_time' => Carbon::now(), //set time of SMS send
                'otp' => $otp
            ]);
            $user->save();
            return $this->success('The message was sent successfully');
        } else {
            return $this->failure('The message failed with status:', [], $message->getStatus());
        }
    }

    //compare otp && verify email
    function compareOtp($request, $user)
    {
        //get the current time use carbon and the stored time in db subtract both 
        //if time was exceed 3 minuts ask user to resend 
        //else get user by phone 
        //update verifyied column to carbon time now 

        $sms_time =  carbon::parse($user->otp_sms_time); // parse function convert string to carbon 
        $currentTime = carbon::now();
        $durationInMinutes = $currentTime->diffInMinutes($sms_time); // time between send SMS and now must not exceed 3 minutes
        if (($user->otp == $request->otp) && $durationInMinutes <= 3) {
            return true;
        }
        return false;
    }

    //compare otp && verifyEmail  
    function byPhoneVerify(OtpRequest $request)
    {

        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user) {
            return $this->failure('user with this phone number doesn\'t exist try with different phone or register again');
        }
        if (!($user->otp && $user->otp_sms_time)) {
            return $this->failure('user otp doesn\'t send to mobile please send sms again');
        }
        if ($user->email_verified_at) {
            return $this->success('your email is already verified'); //you don't need to send email if user already verified his/her email
        }

        $status = $this->compareOtp($request, $user);

        if ($status) {
            $user->update([
                'email_verified_at' => Carbon::now()
            ]);
            $user->save();
            return response(['message' => 'your email verified Successfully', 'otp' => $user->otp]);
        }
        return $this->failure('failed verification your entered code doesn\'t match sms sended code or 3 minutes out try verify by phone again');
    }
    //compare otp && resetPassword
    function byPhoneReset(OtpRequest $request)
    {

        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user) {
            return response(['message' => 'user with this phone number doesn\'t exist try with different phone or register again']);
        }
        if (!($user->otp && $user->otp_sms_time)) {
            return response(['message' => 'user otp doesn\'t send to mobile please send sms again']);
        }

        $status = $this->compareOtp($request, $user); //compare otp

        $user->update([
            'otp_status' => $status
        ]);
        $user->save();

        if ($status) {
            return response(['message' => 'code is correct please enter your new password', 'otp' => $user->otp]);
        }
        return response(['message' => 'code is not correct please try again']);
    }

    function byPhoneResetPasswordForm(OtpRequest $request)
    {
        $user = User::where('otp', $request->otp)->first();
        if (!$user) {
            return response(['message' => 'user with this code doesn\'t exist resend verification code or may be your account accidentaly deleted']);
        }
        if (!($user->otp && $user->otp_sms_time)) {
            return response(['message' => 'user otp doesn\'t send to mobile please send sms again']);
        }

        if (($request->phone == $request->confirm_phone) && $user->otp_status) {
            $user->update($request->validated() + ['otp_status' => 0]);
            $user->save();
            return response(['message' => 'successfully reset your password'], 200);
        }
        return response(['message' => 'failed reset your password'], 401);
    }
}
