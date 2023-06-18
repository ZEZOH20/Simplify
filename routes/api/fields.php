<?php

use App\Http\Controllers\Api\FieldController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'field', 'middleware' => ['verified', 'auth:sanctum']], function () {
    Route::get('/showAll',[FieldController::class,'showFields']);
    Route::get('/show/courses/{field_name}',[FieldController::class,'showFieldCourses']);
    
});

Route::group(['prefix' => 'field', 'middleware' => ['verified', 'auth:sanctum','isAdmin']], function () {
    
    Route::post('/addImg',[FieldController::class,'addImg'])->name('field.addImg');
    Route::get('/removeImg/{field_name}',[FieldController::class,'removeImg'])->name('field.removeImg');

});