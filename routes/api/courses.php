<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

Route::apiResource('/courses', CourseController::class)->middleware(['auth:sanctum', 'verified']);


Route::get('import', [App\Http\Controllers\ImportController::class, 'import']); //temporarily ????????????