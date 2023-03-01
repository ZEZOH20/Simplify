<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

//use Illuminate\Http\Request;
class UserAuthenticationController extends Controller
{
    function register(AuthRequest $request){   //validation - check validation pass - check if user exist
      
       //create user
        //be carful don't use $request->all() it 'll make your sytem unprotected
       $user=User::create([   
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password)
       ]);

       $user->sendEmailVerificationNotification();
        
       return response(['successful registeration'],200);
        
    }
    function login(AuthRequest $request){

        
    }

}
