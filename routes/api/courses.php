<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController as AdminCourseController;

Route::group(['prefix'=>'courses','middleware'=>['auth:sanctum','verified','isAdmin']],function () {
    Route::apiResource('/', AdminCourseController::class);
    Route::get('/status/{course_code}',[AdminCourseController::class,'changeStatus']);
});
