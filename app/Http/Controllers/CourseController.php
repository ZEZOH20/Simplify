<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\CourseCollection;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request);
        $queryParams = $request->query(); // Retrieve query parameters

        if(empty($queryParams))  //update ??? empty($request)
        {
            $courses=Course::all();
            return new Coursecollection($courses) ;
        }
        else if($request->filled('course_type') && $request->filled('credit_hours'))
        {
            $courses=Course::where('course_type',$request->query("course_type"))
            ->where('credit_hours',$request->query("credit_hours"))->get();
            return new Coursecollection($courses) ;
        }
        else if($request->filled('course_type'))
        {
            $courses=Course::where('course_type',$request->query("course_type"))->get();
            return new Coursecollection($courses) ;
        }
        else if($request->filled('credit_hours'))
        {
            $courses=Course::where('credit_hours',$request->query("credit_hours"))->get();
            return new Coursecollection($courses) ;
        }
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $course_code)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $course_code)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $course_code)
    {
        //
    }

    public function changeStatus(string $course_code){
           
         try {
            $course=Course::findOrFail($course_code);
            // do something with $user
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // handle the error
            return response(['message'=>'course with code '.$course_code.' doesn\'t exist in database'],404);
        }

        if($course->status == 'unavailable'){
            $course->status = 'available';
            $course->save();
        }else{
            $course->status = 'unavailable';
            $course->save();
        }
        return response(['message'=>$course->name.' status '. $course->status .' now'],200);
    }
}
