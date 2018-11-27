<?php

namespace App\Http\Requests\Api\Publics;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LiveTrafficGetRequest extends FormRequest
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
            'ip' => 'nullable|ip',
            'useragent' => 'nullable|string|min:1',
            'date' => 'nullable|date|date_format:Y-m-d',
            'per_page' => 'nullable|integer|in:'.env('PER_PAGE_DEFAULT'),
            'page' => 'nullable|integer|min:1'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
