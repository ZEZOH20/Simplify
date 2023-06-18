<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ChangeStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (Auth::check())? true: false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'course_code'=>['required'],
            'status'=>['required','in:finished,active,failed'],
            'score'=>$this->status=='finished' ?['required','string','in:A+,A,A-,B+,B,B-,C+,C,C-,D+,D,D-,F']:'',
        ];
    }
}
