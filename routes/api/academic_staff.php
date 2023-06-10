<?php

use App\Http\Controllers\CRUD\AcademicStaffController as AdminAcademicStaffController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth:sanctum', 'verified', 'isAdmin']], function () {
    Route::apiResource('/staff', AdminAcademicStaffController::class);
    //!!Note : very important to put route name to use  method { Route()->getName() } in validation request
    Route::post('/staff/attachCourse', [AdminAcademicStaffController::class, 'attachDetachCourse'])->name('staff.attachCourse');
    Route::post('/staff/detachCourse', [AdminAcademicStaffController::class, 'attachDetachCourse'])->name('staff.detachCourse');
    Route::get('/staff/showAttached/{email}', [AdminAcademicStaffController::class, 'showAttachedMemberCourses']);
});
