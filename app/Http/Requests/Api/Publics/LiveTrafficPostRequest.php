<?php

namespace App\Http\Requests\Api\Publics;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LiveTrafficPostRequest extends FormRequest
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
            'useragent' => 'required|string|min:1',
            'date' => 'required|date|date_format:Y-m-d',
            'browser' => 'required|string|max:50',
            'os' => 'required|string|max:255',
            'os_code' => 'required|string|max:40',
            'device_type' => 'required|string|max:12',
            'country' => 'required|string|max:120',
            'country_code' => 'required|string|max:2',
            'request_uri' => 'required|string|max:255',
            'referer' => 'required|string|max:255',
            'bot' => 'required|integer',
            'time' => 'required|required|date_format:H:i',
            'uniquev' => 'required|integer',
            'website_id' => 'required|exists:websites,id',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
