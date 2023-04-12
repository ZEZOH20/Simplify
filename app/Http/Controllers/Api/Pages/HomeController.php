<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\HomeCollection;
use App\Http\Resources\api\HomeResource;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    function show(){
        $user =Auth::user();
    
        return new HomeResource($user);
        // return new HomeCollection($user->student->course);

        //  return  HomeResource::collection($user->student->course);
        // return  new HomeResource($user->student->course);
        // return  HomeResource::make($user->student);
    }
}
