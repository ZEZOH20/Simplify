<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadRequest;
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


    public function addPdf(UploadRequest $request)
    {
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
            $pdfFile->move(public_path('pdf/'.$course->course_code.'/'), $pdfFileName);
            //check old file exists in path (delete if exists)
            if (File::exists(public_path('pdf/'.$course->course_code.'/' . $course->material))) {
                $this->deleteFile(public_path('pdf/'.$course->course_code.'/' . $course->material));
            }

            $course->update(["material" => $pdfFileName]);
            return response(['message' => 'file uploaded successfully'], 200);
        }
    }



    public function openPdf(string $file_name)
    {
        return \Response::make(file_get_contents(public_path('pdf/' . $file_name)), 200, [
            'content-type' => 'application/pdf',
        ]);
    }
    public function addImg(UploadRequest $request)
    {
        if ($request->hasFile('img')) {
            try {
                $course = Course::findOrFail($request->course_code);
                // do something with $user
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                // handle the error
                return response(['message' => 'course with code ' . $request->course_code . ' doesn\'t exist in database'], 404);
            }
            // dd($request->file('img'));
            $img_file = $request->file('img');
            $img_file_name = $img_file->getClientOriginalName();
            $img_file->move(public_path('images/courses-images/'.$course->course_code.'/'),$img_file_name);
            //check old file exists in path (delete if exists)
            if (File::exists(public_path('images/courses-images/'.$course->course_code.'/'. $course->img))) {
                $this->deleteFile(public_path('images/courses-images/'.$course->course_code.'/'. $course->img));
            }

            $course->update(["img" => $img_file_name]);
            return response(['message' => 'file uploaded successfully'], 200);
        }
    }

    //??????????????????????????????????
    public function deleteFile(string $path)
    {
        File::delete($path);
    }
    //??????????????????????????????????
}
