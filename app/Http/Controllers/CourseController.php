<?php

namespace App\Http\Controllers;

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
        //
        // dd($request);   
        if(empty($request))
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
