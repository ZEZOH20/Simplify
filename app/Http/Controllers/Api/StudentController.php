<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\CourseResource;
use App\Models\Course;
use App\Http\Requests\Student\StudentRegisterCourseRequest;
use \App\Classes\SimStandardList;
use \App\Classes\GpaCalculator;
use App\Http\Controllers\Api\GpaCalculatorController;
use Illuminate\Http\Request;
use App\Classes\Filtering;
use App\Http\Resources\CourseStudentPivotResource;


class StudentController extends Controller
{
   use SimStandardList, GpaCalculator;


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
            if (!$registerBefore || $registerBefore->pivot->status == 'failed') {
               $availableCourses->forget($itemKey);
            }
         }
         $itemKey++;
      }
      // subtract student registered courses finshed and active from availableCourses
      $studentRegisteredCourses = auth()->user()->student->course()->wherePivot('status', '!=', 'failed')->get(); //collection2
      $difference = $availableCourses->diff($studentRegisteredCourses); //diff between 1 and 2
      return CourseResource::collection($difference);
   }

   //??????????????????????????????????????????????? */
   public function activeCourse(Request $request)
   {
      //Rather than that :- 

      // $activeCourse = auth()->user()->student->course()->wherePivot('status','active')->get();
      // return CourseResource::collection($activeCourse);

      // Do that : -
      $result = (new Filtering($request->query(), 'course_student', [
      'score',
      'term',
      'status',
      'course_code',
      'student_id'
      ]))->start();


      // $result = DB::table('course_student')->where('status', 'finshed')->get();
      return CourseStudentPivotResource::collection($result);
   }
   //??????????????????????????????????????????????????? */

   public function registerCourse(StudentRegisterCourseRequest $request)
   {

      // check if course exist in db
      try {
         $course = $this->checkCourseExistErrorHandler($request->course_code);
      } catch (\Exception $e) {
         return response(['message' => $e->getMessage()]);
      }

      //check if you calculte gpa of previous term so that is mean you finshed previous term registerd course
      // if not you didn\'t pass prev term
      if (!$this->checkTermPass($request)) {
         return response(['message' => 'you didn\'t pass term ' . ($request->term) - 1 . ' to start register on term ' . $request->term], 404);
      }
      //............................

      // check if the user already registered the course or not
      try {
         // register student course 
         $registerdCourse = auth()->user()->student->course();
         $registerdCourse->attach([$request->course_code => ['term' => $request->term]]);
      } catch (\Illuminate\Database\QueryException $e) {
         return response(['message' => 'you already registered course:  ' . $course->name], 404);
      }

      //update exist course_student pivot status into finshed and update score 
      ($request->status == 'finshed' && $request->score) ?
         $registerdCourse->updateExistingPivot(
            $request->course_code,
            [
               'status' => $request->status,
               'score' => SimStandardList::$scores[$request->score],
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
      try {
         $course = $this->checkCourseExistErrorHandler($course_code);
      } catch (\Exception $e) {
         return response(['message' => $e->getMessage()]);
      }
      // UnRegister student course 
      $registerdCourse = auth()->user()->student->course(); //relation

      // check if student registered the course or not to remove it 
      if (!$registerdCourse->find($course_code)) {
         return response(['message' => 'user didn\'t registere ' . $course->name . ' to remove it : '], 404);
      }
      $registerdCourse->detach($course_code); //remove course

      return response(['message' => 'user registered ' . $course->name . ' removed successfully'], 200);
   }


   public function calcGPA(Request $request)
   {
      $request->validate([
         'term' => ['required', 'integer', 'in:1,2,3,4,5,6,7,8']
      ]);

      $result = GpaCalculator::calcGPA($request->term); //GpaCalculator trait
      // $result = (new GpaCalculatorController)->calcGPA($request->term)
      return response([
         'GPA for term(' . $request->term . ') = ' . $result['gpa'],
         'CGPA = ' . $result['cgpa']
      ], 200);
   }
   public function checkTermPass(Request $request)
   {
      $column = 'gpa_t' . ($request->term) - 1;
      $status = auth()->user()->student->course()->wherePivot('status', 'active')->first();
      if (($request->term > 1 && auth()->user()->student->term->$column == null)) { //|| $statu
         return false;
      }
      return true;
   }
   public function changeStatus(Request $request)
   {
      // checking if the course exists in the database
      try {
         $course = Course::findOrfail($request->course_code);
      } catch (\Exception $e) {
         return response([
            'message' => 'course with code ' . $request->course_code . ' doesn\'t exist in database'
         ], 404);
      }
      $registered_courses = auth()->user()->student->course();
      // dd($registered_courses);
      // check if the course is registerd or not
      if (!$registered_courses->find($request->course_code)) {
         return response(['message' => 'this course is not registerd to change it\'s status.'], 404);
      }
      // change the course status according to the value sent with the request
      $registered_courses->updateExistingPivot($request->course_code,['status'=>$request->status]);
      return response([
         'message'=>'status changed successfully'
      ],200);
   }

   public function checkCourseExistErrorHandler(string $course_code)
   {
      $course = Course::find($course_code);
      if (!$course) {
         throw new \Exception('course with code doesnt exists');
      }
      // dd($course);
      return $course;

   }
}