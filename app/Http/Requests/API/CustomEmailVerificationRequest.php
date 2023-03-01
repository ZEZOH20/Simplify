<?php

namespace Illuminate\Foundation\Auth;

use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request; // New code
class CustomEmailVerificationRequest extends FormRequest //Custom EmailVerificationRequest Class after update
{
    private User $user;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    function __construct(Request $request)
    { // New code
         try{
        $this->user = User::findOrFail($request->id);

    }catch(\Exception $ex){
       throw new Exception("User not registered before to verify email unexpected error try register again");
       return redirect(Route('login-view'))->with(['message'=>"User not registered before to verify email unexpected error try register again"]);
    }
      // ******* New code  
        
    }
    // New code

    // replace each $this->User() by $this->user
    
   // ******* New code 
    public function authorize()
    { 
        
        
        if (! hash_equals((string) $this->user->getKey(), (string) $this->route('id'))) {
            return false;
        }

        if (! hash_equals(sha1($this->user->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill()
    {
        if (! $this->user->hasVerifiedEmail()) {
            $this->user->markEmailAsVerified();

            event(new Verified($this->user));
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        return $validator;
    }
}
