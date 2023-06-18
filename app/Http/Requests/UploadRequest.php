<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (Auth::check())? true: false;    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $route_name=$this->Route()->getName();
        $field_img_rules=[
            'field_name' =>'required|string',
            'img' => 'required|mimes:png,jpg,jpeg|max:50048'
        ];
        $course_img_rules=[
            'course_code' =>'required|string',
            'img' => 'required|mimes:png,jpg,jpeg|max:50048'
        ];
        $pdf_rules=[
            'course_code'=>'required|string',
            'pdf' => 'required|mimes:pdf|max:50048'
        ];
        $excel_rules=[
            'excel_file' => 'required|mimes:csv,xlx,xls|max:50048'
        ];

        return ($route_name == 'admin.addExcelFile') ? $excel_rules : (
            ($route_name == 'course.addImg') ? $course_img_rules : (
            ($route_name == 'course.addPdf') ? $pdf_rules : (
            ($route_name == 'field.addImg') ? $field_img_rules : ''
            ) 
            )
        ) ;
    }
}
