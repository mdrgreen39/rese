<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'comment' => ['required', 'integer', 'max:400'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'image' => ['nullable', 'mimes:jpeg,png', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => 'コメントは必須です',
            'comment.max' => 'コメントは400文字以内で入力してください',
            'rating.required' => '評価は必須です',
            'rating.integer' => '評価は整数で入力してください',
            'rating.min' => '評価は1以上で入力してください',
            'rating.max' => '評価は5以下で入力してください',
            'image.mimes' => '画像はjpegまたはpng形式でアップロードしてください',
            'image.max' => '画像のサイズは2MB以下でなければなりません',
        ];
    }
}
