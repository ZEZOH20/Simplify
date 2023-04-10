<?php

use App\Http\Controllers\Crud\StudentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>['verified','auth:sanctum']],function(){
    Route::apiResource('students',StudentController::class);   //, ["except" => ["create", "edit"]]
});
 
//GET	/sharks/create
//GET	/sharks/{id}/edit