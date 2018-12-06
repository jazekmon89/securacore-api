<?php

namespace App\Http\Requests\Api\Publics;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchRequest extends FormRequest
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
            'table' => 'required|string|min:1',
            'text' => 'nullable|string|min:1',
            'per_page' => 'nullable|integer|in:'.env('PER_PAGE_DEFAULT'),
            'page' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'required_with:start_date|date|date_format:Y-m-d|after_or_equal:start_date',
            'date' => 'nullable|date|date_format:Y-m-d',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
