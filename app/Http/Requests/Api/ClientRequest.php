<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'url' => 'required|string|max:255',
            'public_key' => 'nullable|string|max:255',
            'is_activated' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'status' => 'nullable|boolean',
            'is_checked' => 'nullable|boolean'
        ];
    }
}
