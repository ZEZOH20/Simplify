<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadRequest;
use App\Http\Resources\api\CourseResource;
use App\Http\Resources\api\FieldResource;
use App\Http\Resources\CourseStudentPivotResource;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class FieldController extends Controller
{
    //
     //show all fields
   public function showFields()
   {
      //getting all the fields
      $fields=Field::all();
      return FieldResource::collection($fields);

   }

   //show field related courses
   public function showFieldCourses(string $field_name)
   {
      //check if field name is valid or not
     try{
         $field=Field::findOrFail($field_name);
      }
      catch(\Exception $e)
      {
         return response(['message'=>'Couldn\'t find a field with such name'],404);
      }
      
      $courses=$field->course;
      //check if this field have courses or not
      if($courses->count()==0)
      {
         return response(['message'=>'Couldn\'t find courses related to this field'],404);
      }
      // return CourseStudentPivotResource::collection($courses);
      return CourseResource::collection($courses);
   }
   public function getCourseDetails(string $course_code)
   {
      try {
         $course = $this->checkCourseExistErrorHandler($course_code);
      } catch (\Exception $e) {
         return response(['message' => $e->getMessage()]);
      }
      return new CourseStudentPivotResource($course);
   }

   public function addImg(UploadRequest $request)
   {
      try{
         $field=Field::findOrFail($request->field_name);
      }
      catch(\Exception $e)
      {
         return response(['message'=>'Couldn\'t find a field with such name'],404);
      }

      if ($request->hasFile('img')) {
         try{
            $field=Field::findOrFail($request->field_name);
         }
         catch(\Exception $e)
         {
            return response(['message'=>'Couldn\'t find a field with such name'],404);
         }
         // dd($request->file('img'));
         $img_file = $request->file('img');
         $img_file_name = $img_file->getClientOriginalName();
         $img_file->move(public_path('images/field-images/'.$field->name.'/'),$img_file_name);
            //check old file exists in path (delete if exists)
            if (File::exists(public_path('images/field-images/'.$field->name.'/'. $field->img))) {
                $this->deleteFile(public_path('images/field-images/'.$field->name.'/'. $field->img));
            }
         $field->update(["img" => $img_file_name]);
         return response(['message' => 'file uploaded successfully'], 200);
     }

   }

   public function deleteFile(string $path)
   {
       File::delete($path);
   }
}
