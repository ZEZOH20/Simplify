<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OtpRequest extends FormRequest
{
    var $validation_rules = [] ;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $routeName = $this->route()->getName();
        if($routeName=='byEmailverify'){
            $this->validation_rules = [
                'email'=>[
                    'required','email','max:255'
                    ]
            ];
        }else if($routeName=='sendOtp'){
            $this->validation_rules = [
                'phone_number'=>[
                    'required','numeric','digits_between:10,12'
                ]
            ];
        }else if($routeName=='compareOtp'){
            $this->validation_rules = [
                'phone_number'=>[
                    'required','numeric','digits_between:10,12'
                ],
                'otp'=>[
                    'required','numeric','digits:4'
                ]
            ];
        }else if($routeName=='byPhoneResetPasswordForm'){
            $this->validation_rules = [
                'password' => [
                    'required', 'max:8'
                ],
                'password_confirm' => [
                    'required', 'same:password'  // required and has to match the password field
                ],
            ];
        }
        return  $this->validation_rules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors'=>$validator->errors()
          ],422));
    }
}
