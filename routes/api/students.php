<?php

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Crud\StudentController as AdminStudentController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/students', 'middleware' => ['verified', 'auth:sanctum', 'isAdmin']], function () {
    Route::apiResource('/', AdminStudentController::class);   //, ["except" => ["create", "edit"]]
    Route::post('/addExcelFile',[StudentController::class,'addExcelFile'])->name('admin.addExcelFile');
});
Route::group(['prefix' => 'student', 'middleware' => ['verified', 'auth:sanctum']], function () {
    Route::get('/available/course', [StudentController::class, 'avaliableCourse']);   //, ["except" => ["create", "edit"]]
    Route::get('/active/course', [StudentController::class, 'activeCourse']);
    Route::post('/register/course', [StudentController::class, 'registerCourse']);
    Route::post('/unRegister/course/{course_code}', [StudentController::class, 'unRegisterCourse']);
    Route::post('/calc/gpa', [StudentController::class, 'calcGPA']);
    Route::post('/change/status',[StudentController::class,'changeStatus']);
    Route::get('/add/field',[StudentController::class,'addField']);
    Route::get('/remove/field/{field_name}',[StudentController::class,'removeField']);
    Route::get('/show/fields',[StudentController::class,'showFields']);
    Route::get('/showInfo',[StudentController::class,'showInfo']);
    // Route::get('/show/progress/{course_code}',[StudentController::class,'updateProgress']);

    // testing draft
    Route::get('/show/progress/{course_code}',[StudentController::class,'updateProgress']);
    Route::get('/show/progress/',[StudentController::class,'checkProgress']);

    //
    

});
