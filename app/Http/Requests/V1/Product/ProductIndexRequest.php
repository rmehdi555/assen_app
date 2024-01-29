<?php

namespace App\Http\Requests\V1\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'exchange' => 'nullable|string',
            'factory_slug' => 'nullable|string',
            'standard_slug' => 'nullable|string',
            'size_slug' => 'nullable|string',
            'exchange_slug' => 'nullable|string',
            'q' => 'nullable|string',
            'count' => 'nullable|integer',
        ];
    }
}
