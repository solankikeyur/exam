<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\User;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = new User();
        
        return [
            "first_name" => "required",
            "last_name" =>  "required",
            "status"    =>  "required|numeric",
            "password"  =>  "required|min:6|confirmed",
            "mobile_no" =>  "required|numeric|digits:10",
            'email' =>  "required|email|unique:".$user->getTable().",email",
            "image" => "image"
        ];
    }

    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
         'type'   => "error",
         'message'      => $validator->errors()
       ]));
    }
}
