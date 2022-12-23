<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'fullname' => 'required|string|max:255',
            'avatar_url' => 'required|string',
        ];
    }

}