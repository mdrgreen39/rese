<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
        $now = new \DateTime();
        $currentTime = $now->format('H:i');
        $currentDate = $now->format('Y-m-d');

        return [
            'date' => ['required', 'date'],
            'time' => ['required', 'string', 'date_format:H:i', function ($attribute, $value, $fail) use ($currentTime, $currentDate) {
                $inputDate = request('date');

                // 当日の予約の場合、現在の時刻を超えていないかチェック
                if ($inputDate === $currentDate && $value < $currentTime) {
                    $fail('当日の予約時間は現在時刻を過ぎているため、予約できません。');
                }
            }],
            'people' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'date.required' => '日付を選択してください',
            'date.date' => '有効な日付を選択してください',
            'time.required' => '時間を選択してください',
            'time.string' => '時間は文字列として入力してください',
            'time.date_format' => '時間は24時間形式で HH:MM で入力してください',
            'people.required' => '人数を選択してください',
            'people.integer' => '人数は整数で入力してください',
            'people.min' => '人数は少なくてとも1人である必要があります',
        ];

    }
}
