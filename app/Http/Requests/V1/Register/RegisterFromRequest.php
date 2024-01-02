<?php

namespace App\Http\Requests\V1\Register;


use Illuminate\Foundation\Http\FormRequest;


class RegisterFromRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'nationalcode' => 'nullable|size:10',
            'email' => 'nullable|email',
            'phone_number' => 'nullable|string',
            'telegram_user' => 'nullable|string',
        ];
    }
}
