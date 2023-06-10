<?php

use App\Http\Controllers\Api\FieldController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'field', 'middleware' => ['verified', 'auth:sanctum']], function () {
    Route::get('/showAll',[FieldController::class,'showFields']);
    Route::get('/show/courses/{field_name}',[FieldController::class,'showFieldCourses']);
    
});