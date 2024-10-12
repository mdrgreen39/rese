<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'unique:users,email', 'string', 'email', 'max:191'],
            'password' => ['required', 'min:8', 'max:191'],
            'role' => ['required', 'in:store_manager'],
            ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前を入力してください',
            'name.string' => '名前を文字列で入力してください',
            'name.max' => '名前を191文字以下で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.unique' => 'このアカウントはすでに登録済みです',
            'email.string' => 'メールアドレスを文字列で入力してください',
            'email.email' => '有効なメールアドレス形式を入力してください',
            'email.max' => 'メールアドレスを191文字以下で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードを8文字以上で入力してください',
            'password.max' => 'パスワードを191文字以下で入力してください',
            'role.required' => 'ロールを選択してください',
            'role.in' => '選択されたロールは無効です',
        ];
    }
}
