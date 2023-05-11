<?php

namespace App\Http\Resources\api;

use App\Models\Course;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
     
        // dd(Field::first()->sub_fields());
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name,

            "student" => [
                'sex'=>$this->student->gender,
                'img'=>$this->student->img,
            ],
            "fields" => FieldResource::collection(Field::where('sub_field_name','=',null)->get()),
            
            "student_fields_progress"=> FieldResource::collection($this->student->field()
                                            ->where('fields.sub_field_name','=',null)->get()),
          //->where('field_student.student_id','=', $this->student->id)->get()), 
        ];
    }
}

//"coursecount" => Course::with('prereq')->get()->count()

                             // Field::with('related_fields')->get()
            // "registered_courses" => CourseCollection::make($this->student->course), 
                                    //CourseResource::collection($this->student->course)

//  'prereq'=>CourseResource::make(Course::find(6)->prereq_related_to) بيرجع حاجه واحده
//   'prereq'=>CourseResource::collection(Course::find(1)->prereq) بيرجع حاجات
//  "prereq" => CourseCollection::make(Course::find(4)->prereq_related_to)  بيرجع حاجه واحده مش شغال
// "prereq" => CourseCollection::make(Course::find(4)->prereq_related_to) بيرجع حاجات