<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\CourseCollection;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Classes\Filtering;
use Illuminate\Support\Facades\File;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $courses = Course::with(['prereq'])->get();
        return new CourseCollection($courses);
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

    public function changeStatus(string $course_code)
    {

        try {
            $course = Course::findOrFail($course_code);
            // do something with $user
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // handle the error
            return response(['message' => 'course with code ' . $course_code . ' doesn\'t exist in database'], 404);
        }

        if ($course->status == 'unavailable') {
            $course->status = 'available';
            $course->save();
        } else {
            $course->status = 'unavailable';
            $course->save();
        }
        return response(['message' => $course->name . ' status ' . $course->status . ' now'], 200);
    }
    public function addPdf(Request $request)
    {
        // dd($request->pdf->file_original_name);
        // dd($request);
        // $course=Course::findorfail($request->courseCode);
        // $file=$request->pdf;
        // dd($file->getClientOriginalName());
        // $course->update(["material"=>$file]);

        if ($request->hasFile('pdf')) {
            try {
                $course = Course::findOrFail($request->course_code);
                // do something with $user
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                // handle the error
                return response(['message' => 'course with code ' . $request->course_code . ' doesn\'t exist in database'], 404);
            }
            $pdfFile = $request->file('pdf');
            $pdfFileName = $pdfFile->getClientOriginalName();
            $pdfFile->move(public_path('uploadedpdfs'), $pdfFileName);
            $course->update(["material" => $pdfFileName]);
            return response(['message' => 'file uploaded successfully'], 200);
        }
    }
    public function deletePdf(string $course_code)
    {
        try {
            $course = Course::findOrFail($course_code);
            // do something with $user
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // handle the error
            return response(['message' => 'course with code ' . $course_code . ' doesn\'t exist in database'], 404);
        }
        File::delete(public_path('uploadedpdfs/') . $course->material);
        // dd($course->material);
        $course->update(['material' => NULL]);
        return response(['message' => 'file deleted successfully'], 200);
    }

    public function openPdf(string $file_name)
    {
        return Response::make(file_get_contents(public_path('pdf/' . $file_name)), 200, [
            'content-type' => 'application/pdf',
        ]);
    }
}
