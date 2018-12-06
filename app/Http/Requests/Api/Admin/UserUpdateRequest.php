<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
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
        $user = $this->route('user');
        return [
            'first_name' => 'nullable|string|max:191',
            'last_name' => 'nullable|string|max:191',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'username' => 'nullable|string|max:191',
            'role' => 'nullable|integer',
            'status' => 'nullable|boolean'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
