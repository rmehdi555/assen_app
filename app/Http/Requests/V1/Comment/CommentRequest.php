<?php

namespace App\Http\Requests\V1\Comment;

use App\Enum\CommentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'comment'=>'required|string',
            'phone'=>'nullable|string',
            'rate'=>'nullable|integer|min:1|digits_between: 1,5|max:5',
            'type'=>[Rule::enum(CommentType::class)],
            'type_slug'=>'required|string',
        ];
    }
}
