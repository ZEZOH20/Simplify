<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('/show')
->controller(CourseController::class)
->group(function()
{
    Route::get('/all','showAll');
    Route::get('/{type}','showSelection');
   
});
Route::get('/transform',[CourseController::class,'transform']);

// Route::get('/show/{type}',[CourseController::class,'showSelection']);
