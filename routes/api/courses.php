<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController as AdminCourseController;
use App\Http\Controllers\Api\CourseController;

//AdminStudent
Route::group(['prefix' => 'courses', 'middleware' => ['auth:sanctum', 'verified', 'isAdmin']], function () {
    Route::apiResource('/', AdminCourseController::class);
    Route::get('/status/{course_code}', [AdminCourseController::class, 'changeStatus']);
    Route::post('/addPdf', [AdminCourseController::class, 'addPdf'])->name('course.addPdf');
    Route::get('/deletePdf/{course_code}', [AdminCourseController::class, 'deletePdf'])->name('course.deletePdf');
    Route::post('/addImg', [AdminCourseController::class, 'addImg'])->name('course.addImg');
    Route::get('/removeImg/{course_code}', [AdminCourseController::class, 'removeImg'])->name('course.removeImg');
});

//Student
Route::group(['prefix' => 'courses', 'middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/showAttached/{course_code}', [CourseController::class, 'showAttachedCourseMembers']);
});
