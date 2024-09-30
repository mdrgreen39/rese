<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest
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
            'users' => ['required_without:roles', 'nullable', 'array' ],
            'roles' => ['prohibits:users', 'nullable', 'exists:roles,id'],
            'subject' => ['required', 'string', 'max:191'],
            'message' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'users.array' => '利用者の選択は配列形式である必要があります',
            'roles.exists' => '選択されたロールは存在しません',
            'subject.required' => '件名は必須項目です',
            'subject.string' => '件名は文字列である必要があります',
            'subject.max' => '件名は191文字以内で入力してください',
            'users.required_without' => '個別の利用者を選択するか、ロールを選択してください',
            'roles.prohibits' => '個別の利用者かロール、どちらかだけを選択してください',
            'message.required' => 'メッセージ本文は必須項目です',
            'message.string' => 'メッセージ本文は文字列である必要があります',
        ];
    }
}
