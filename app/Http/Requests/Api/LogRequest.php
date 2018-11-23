<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LogRequest extends FormRequest
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
            'ip' => 'required|ip',
            'date' => 'required|date|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'page' => 'nullable|string|min:1',
            'query' => 'nullable|string|min:1',
            'type' => 'required|string|max:50',
            'browser_name' => 'nullable|string|max:255',
            'browser_code' => 'nullable|string|max:50',
            'os_name' => 'nullable|string|max:255',
            'os_code' => 'nullable|string|max:40',
            'country' => 'required|string|max:120',
            'country_code' => 'required|string|max:2',
            'region' => 'nullable|string|max:120',
            'city' => 'nullable|string|max:120',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'isp' => 'nullable|string|max:255',
            'user_agent' => 'nullable|string|min:1',
            'referer_url' => 'nullable|url',
            'website_id' => 'required|integer|exists:websites,id',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
