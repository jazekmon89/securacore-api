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
            'time' => 'required|date_format:H:i:s',
            'page' => 'required|string|min:1',
            'query' => 'required|string|min:1',
            'type' => 'required|string|max:50',
            'browser_name' => 'required|string|max:255',
            'browser_code' => 'required|string|max:50',
            'os_name' => 'required|string|max:255',
            'os_code' => 'required|string|max:40',
            'country' => 'required|string|max:120',
            'country_code' => 'required|string|max:2',
            'region' => 'required|string|max:120',
            'city' => 'required|string|max:120',
            'latitude' => 'required|integer',
            'longitude' => 'required|integer',
            'isp' => 'required|string|max:255',
            'user_agent' => 'required|string|min:1',
            'referer_url' => 'required|url',
            'website_id' => 'required|integer|exists:websites,id',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
