<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BanIPPostRequest extends FormRequest
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
            'ip' => 'required|ip',
            'date' => 'required|date',
            'time' => 'date_format:H:i:s',
            'reason' => 'required|string|min:3',
            'url' => 'required|url',
            'website_id' => 'required|integer|exists:websites,id'
        ];
    }
}
