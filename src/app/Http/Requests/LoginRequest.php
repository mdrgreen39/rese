<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'exists:users,email', 'string', 'email', 'max:191'],
            'password' => ['required', 'min:8', 'max:191'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.unique' => 'このアカウントはすでに登録済みです',
            'email.string' => 'メールアドレスを文字列で入力してください',
            'email.email' => '有効なメールアドレス形式を入力してください',
            'email.max' => 'メールアドレスを191文字以下で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードを8文字以上で入力してください',
            'password.max' => 'パスワードを191文字以下で入力してください',
        ];
    }
}
