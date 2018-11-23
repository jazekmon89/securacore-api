<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SecurityUpdate extends FormRequest
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
            'detection' => 'nullable|boolean',
            'badbot' => 'nullable|boolean',
            'fakebot' => 'nullable|boolean',
            'useragent_header' => 'nullable|boolean',
            'function' => 'nullable|json',
            'enabled' => 'nullable|boolean',
            'security' => 'nullable|boolean',
            'proxy' => 'nullable|boolean',
            'proxy_headers' => 'nullable|boolean',
            'ports' => 'nullable|boolean',
            'sql_injection' => 'nullable|boolean',
            'xss' => 'nullable|boolean',
            'clickjacking' => 'nullable|boolean',
            'mime_mismatch' => 'nullable|boolean',
            'https' => 'nullable|boolean',
            'data_filtering' => 'nullable|boolean',
            'sanitation' => 'nullable|boolean',
            'php_version' => 'nullable|boolean',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
