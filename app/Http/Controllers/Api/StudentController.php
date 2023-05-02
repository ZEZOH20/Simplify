<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\CourseResource;
use App\Models\Course;
use  App\Http\Requests\Student\StudentRegisterCourseRequest;
use \App\Classes\SimStandardList;

class StudentController extends Controller
{
   use SimStandardList;

   public function avaliableCourse()
   {

      //  return only avaliable courses and don't return finshed courses and active
      // $availableCourses = Course::with('prereq')->where('status', 'available')->get(); //collection1
      $availableCourses = Course::where('status', 'available')->get();
      $itemKey = 0; //availableCourses collection iterator key

      //check if avaliable course has prerequest course if has then :
      // check if student finshed this prerequest course if not finshed then :
      // remove course from availableCourses collection because student must finsh prerequest to register the course    
      foreach ($availableCourses as $course) {
         if ($course->prereq_code) {
            $registerBefore = auth()->user()->student->course()->wherePivot('course_code', $course->prereq_code)->first();
            if (!$registerBefore) { //logic error in registerBefore
               $availableCourses->forget($itemKey);
            }
         }
         $itemKey++;
      }
      // subtract student registered courses finshed and active from availableCourses
      $studentRegisteredCourses = auth()->user()->student->course; //collection2
      $difference = $availableCourses->diff($studentRegisteredCourses); //diff between 1 and 2
      return CourseResource::collection($difference);
   }


   public function registerCourse(StudentRegisterCourseRequest $request)
   {
      // check if course exist in db
      $course = Course::where('course_code', $request->course_code)->first();
      $this->checkCourseExistErrorHandler($course, $request->course_code);

      // check if the user already registered the course or not
      try {
         // register student course 
         $registerdCourse = auth()->user()->student->course();
         $registerdCourse->attach($request->course_code);
      } catch (\Illuminate\Database\QueryException $e) {
         return response(['message' => 'user already registered course :  ' . $course->name], 404);
      }

      //update exist course_student pivot status into finshed and update score 
      ($request->status == 'finshed' && $request->score) ?
         $registerdCourse->updateExistingPivot(
            $request->course_code,
            [
               'status' => $request->status,
               'score' => SimStandardList::$scores[$request->score]
            ]
         )
         : '';
      return response([
         'message' => $course->name . ' for student : ' . auth()->user()->name . ' successfully registered'
      ], 200);
   }

   public function unRegisterCourse(string $course_code)
   {
      // check if course exist in db
      $course = Course::where('course_code', $course_code)->first();
      $this->checkCourseExistErrorHandler($course, $course_code);


      // UnRegister student course 
      $registerdCourse = auth()->user()->student->course(); //relation

      // check if student registered the course or not to remove it 
      if (!$registerdCourse->find($course_code)) {
         return response(['message' => 'user didn\'t registere ' . $course->name . ' to remove it : '], 404);
      }
      $registerdCourse->detach($course_code); //remove course

      return response(['message' => 'user registered ' . $course->name . ' removed successfully'], 200);
   }


   public function checkCourseExistErrorHandler(Course $course, string $course_code)
   {
      if (!$course) {
         return response([
            'message' => 'course with code ' . $course_code . ' doesn\'t exist in database'
         ], 404);
      }
   }
}
