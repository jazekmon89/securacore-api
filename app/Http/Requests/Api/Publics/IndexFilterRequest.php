<?php

namespace App\Http\Requests\Api\Publics;

use Illuminate\Foundation\Http\FormRequest;

class IndexFilterRequest extends FormRequest
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
            'per_page' => 'nullable|integer|in:'.env('PER_PAGE_DEFAULT'),
            'page' => 'nullable|integer|min:1'
        ];
    }
}
