<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    
    public function showAttachedCourseMembers(string $course_code)
    {
        $course = Course::with(['whosResponsible'])->where('course_code', $course_code)->first();
        //check exists 
        if (!$course)
            response(['errorMessage' => 'course' . $course_code . ' doesn\'t exist'], 400);

        return new CourseResource($course);   
    }
}
