<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\ChangeStatusRequest;
use App\Http\Resources\api\CourseResource;
use App\Http\Resources\StudentFieldResource;
use App\Models\Course;
use App\Http\Requests\Student\StudentRegisterCourseRequest;
use \App\Classes\SimStandardList;
use \App\Classes\GpaCalculator;
use App\Models\Field;
use Illuminate\Http\Request;
use App\Classes\Filtering;
use App\Events\AddFieldEvent;
use App\Events\ChangeStatusEvent;
use App\Events\StudentRegisterCourseEvent;
use App\Events\StudentUnRegisterCourseEvent;
use App\Http\Resources\CourseStudentPivotResource;
use Illuminate\Support\Facades\DB;


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
      ($request->status == 'finished' && $request->score) ?
         $registerdCourse->updateExistingPivot(
            $request->course_code,
            [
               'status' => $request->status,
               'score' => SimStandardList::$scores[$request->score],
            ]
         )
         : '';
      // Fire the StudentRegisterCourseEvent event
      event(new StudentRegisterCourseEvent());   

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

      // Fire the StudentUnRegisterCourseEvent event
      event(new StudentUnRegisterCourseEvent());  

      return response(['message' => 'user registered ' . $course->name . ' removed successfully'], 200);
   }

//comment
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
   public function changeStatus(ChangeStatusRequest $request)
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
      ($request->score=='')?
      $registered_courses->updateExistingPivot($request->course_code, ['status' => $request->status]) :
      $registered_courses->updateExistingPivot($request->course_code, ['status' => $request->status,'score'=>SimStandardList::$scores[$request->score]])
      ;

      // Fire the ChangeStatusEvent event
      event(new ChangeStatusEvent());  
      return response([
         'message' => 'status changed successfully'
      ], 200);
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

   public function addField(string $field_name)
   {
      // check for the field existance
      try{
         $field=Field::findOrFail($field_name);
      }
      catch(\Exception $e)
      {
         return response(['message'=>'Couldn\'t find a field with such name'],404);
      }
      $student= auth()->user()->student;
      // check if the field already exists in the pivot table
      if(!$student->field()->find($field_name))
      {
      $student->field()->attach($field);
      }
      else{
         return response(['message'=>'Field is already added'],404);
      }

      //Fire the AddFieldEvent event
      event(new AddFieldEvent());
      return response(['message'=>'Field was added successfully'],200);
   }

   public function removeField(string $field_name)
   {
      try{
         $field=Field::findOrFail($field_name);
      }
      catch(\Exception $e)
      {
         return response(['message'=>'Couldn\'t find a field with such name'],404);
      }
      $student=auth()->user()->student;
      try{
         $student->field()->findOrFail($field_name);
      }
      catch(\Exception $e)
      {
         return response(['message'=>'This field doesn\'t exist in your profile'],404);
      }
      $student->field()->detach($field);
      return response(['message'=>'Field was removed successfully'],200);
   }

   public function showFields()
   {
      $student=auth()->user()->student;
      // dd($student->field->pivot);
      return StudentFieldResource::collection($student->field);
      // return $student->field;
   }

   
   //comment
   public function automatedCheckProgress()
   {
      // student fields 
      $fields=auth()->user()->student->field;
      // getting student id
      $student_Id=auth()->user()->student->id;
      foreach($fields as $field)
      {
         // joining table between course_field and course student table to get status of every course
         $join_table=DB::table('course_field')
         ->join('course_student','course_field.course_code','=','course_student.course_code')->select('course_student.*','course_field.*');
         // get field courses
         $courses=$field->course;
         //check if the field has courses or not
         if($courses->count()==0)
         {
            continue;
         }
         // field courses registered by this student
         $join_courses=$join_table->where(['student_id'=>$student_Id,'field_name'=>$field->name]);
         // get number of finished courses in this field by this student
         $finished_courses=$join_courses->where(['status'=>'finished']);
         $finished_courses_count=$finished_courses->count();
         $progress=(($finished_courses_count)/($courses->count()))*100;
         $field->pivot->update(['progress'=>$progress]);
      }
      
   }
}

// public function updateProgress(string $course_code)
   // {
   //    $course=Course::findOrfail($course_code);
   //    $related_field=$course->field()->first();
   //    // // dd($related_field->name);
   //    $courses=$related_field->course;
   //    // // dd($courses);
   //    // $student_courses=auth()->user()->student->course;
   //    // // dd($student_courses);
   //    // // dd($courses->with('students')->get);
   //    $join_table=DB::table('course_field')
   //    ->join('course_student','course_field.course_code','=','course_student.course_code')->select('course_student.*','course_field.*');
   //    // dd($joinTable);      
   //    $student_Id=auth()->user()->student->id;
   //    $join_courses=$join_table->where(['student_id'=>$student_Id,'field_name'=>$related_field->name]);
   //    // dd($joinCourses);
   //    $finished_courses=$join_courses->where(['status'=>'finished'])->get();
   //    $finished_courses_count=$finished_courses->count();
   //    $progress=(($finished_courses_count)/($courses->count()))*100;
   //    // dd($finishedCoursesCount);
   //    dd($progress);
   // }