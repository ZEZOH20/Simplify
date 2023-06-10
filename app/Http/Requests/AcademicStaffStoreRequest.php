<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcademicStaffStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (auth()->user()) ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $routeName = $this->Route()->getName();

        $storeRules = [
            'name' => ['required', 'string', 'max:20'],
            'verbose_title' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone_number' => ['number'],
            'img' => ['image', 'size:1024', 'mimes:jpeg,png'],
            'department' => ['required', 'string'],
            'degree' => ['required', 'string'],
            'title' => ['required', 'in:professor,instructor'],
        ];
        $updateRules = [
            'name' => ['string', 'max:20'],
            'verbose_title' => ['string'],
            'phone_number' => ['number'],
            'email' => ['email'],
            'img' => ['image', 'size:1024', 'mimes:jpeg,png'],
            'department' => ['string'],
            'degree' => ['string'],
            'title' => ['in:professor,instructor'],
        ];
        $attachDetachCourseRules=[
            'course_code'=>['required','digits:7','integer'],
            'email' => ['required', 'email'],
        ];
     
        return ($routeName == 'staff.store') ?  $storeRules : (
            ($routeName == 'staff.update') ? $updateRules : (
                ($routeName == 'staff.attachCourse' || $routeName == 'staff.detachCourse') ? $attachDetachCourseRules : ''
            )
        );
    }
}
