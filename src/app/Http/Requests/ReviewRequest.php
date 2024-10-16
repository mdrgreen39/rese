<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'rating' => ['required', 'integer', 'max:5'],
            'comment' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価は必須項目です',
            'rating.integer' => '評価は整数でなければなりません',
            'rating.max' => '評価は最大5までです',
            'comment.required' => 'コメントを入力してください',
            'comment.string' => 'コメントは文字列でなければなりません',
            'comment.max' => 'コメントは最大500文字までです',
        ];
    }
}
