<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

//use App\Http\Requests\API\FormRequest;   // custom return json error rather than page (session) 

class AuthRequest extends FormRequest
{
    var $validation_rules=[];
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;  // xxxxxx
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // 'name'=>'required'
        $routeName = $this->route()->getName();
        if ($routeName == 'register') {
            $this->validation_rules = [
                'email' => [
                    'required', 'max:255', 'email ', 'unique:users'
                ],
                'password' => [
                    'required', 'max:8'
                ],

                'password_confirm' => [
                    'required', 'same:password'  // required and has to match the password field
                ],
                'name' => [
                    'required'
                ],
                'phone_number'=>[
                    'required','numeric','digits_between:10,12','unique:users'
                ],
                'collage_id'=>[
                     'required','numeric','min:203000','unique:students'
                ],
                'gender'=>[
                    'required','string'
                ]
            ];
              
            return $this->validation_rules;
           
        }
        //else login
        $this->validation_rules = [
            'email' => [
                'required', 'max:255', 'email ',
            ],
            'password' => [
                'required', 'max:8'
            ],
        ];

        return $this->validation_rules;
    }

    public function messages() //custom error messages
    {
        return [
           'password.max'=>'your password must less than 8'  
        ];
    }
 
    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
  
}
