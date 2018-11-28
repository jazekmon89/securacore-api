<?php

namespace App\Http\Requests\Api\Publics;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
        return [
            'public_key' => 'required|string|min:1',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
