<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => ['required', 'integer', 'exists:emails,id'],
            'is_read' => ['sometimes', 'boolean'],
            'is_archived' => ['sometimes', 'boolean'],
            'is_deleted' => ['sometimes', 'boolean'],

        ];
    }
}
