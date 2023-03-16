<?php

namespace App\Http\Requests;

//use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\API\FormRequest;   // custom return json error rather than page (session) 

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;  // xxxxxx
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
            $validation_rules = [
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
                ]
            ];
              
            return $validation_rules;
           
        }
        //else login
        $validation_rules = [
            'email' => [
                'required', 'max:255', 'email ',
            ],
            'password' => [
                'required', 'max:8'
            ],
        ];

        return $validation_rules;
    }

    public function messages() //custom error messages
    {
        return [
           'password.max'=>'your password must less than 8'  
        ];
    }
  
}
