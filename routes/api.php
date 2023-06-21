<?php

use App\Http\Controllers\Api\Pages\HomeController;
use App\Http\Controllers\RecommendationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

 
//use App\Classes\CustomValidation;
//
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

//Include separate routes files 

$files = glob(base_path('routes/api/*.php'), GLOB_BRACE);
foreach ($files as $file)
    require $file;

//Include separate routes files    


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ( home ) , ( dashboard ) page
Route::group(['middleware'=>['auth:sanctum','verified','isAdmin']],function(){
    Route::get('/dashboard',function(){ //dashboard
        echo 'dashboard page';
    })->name('dashboard');
});


Route::group(['middleware'=>['auth:sanctum','verified']],function(){
    Route::post('test',function(){ //test
        (new RecommendationController)->start();
    });
    Route::get('/home',[HomeController::class,'show'])->name('home'); //home
});

