<?php

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Crud\StudentController as AdminStudentController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/students', 'middleware' => ['verified', 'auth:sanctum', 'isAdmin']], function () {
    Route::apiResource('/', AdminStudentController::class);   //, ["except" => ["create", "edit"]]
});
Route::group(['prefix' => 'student', 'middleware' => ['verified', 'auth:sanctum']], function () {
    Route::get('/available/course', [StudentController::class, 'avaliableCourse']);   //, ["except" => ["create", "edit"]]
    Route::get('/active/course', [StudentController::class, 'activeCourse']);
    Route::post('/register/course', [StudentController::class, 'registerCourse']);
    Route::post('/unRegister/course/{course_code}', [StudentController::class, 'unRegisterCourse']);
    Route::post('/calc/gpa', [StudentController::class, 'calcGPA']);
    Route::post('/change/status',[StudentController::class,'changeStatus']);
});


 
//GET	/sharks/create
//GET	/sharks/{id}/edit