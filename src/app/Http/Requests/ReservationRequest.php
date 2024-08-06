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
        $minDatetime = (clone $now)->modify('+3 hours');

        return [
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i', function ($attribute, $value, $fail) use ($minDatetime) {
                $inputDate = \DateTime::createFromFormat('Y-m-d', $value);
                if ($inputDate < $minDatetime) {
                    $fail('現在時刻から3時間後以降でなければなりません。');
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
            'time.date_format' => '時間は24時間形式で HH:MM で入力してください',
            'people.required' => '人数を入力してください',
            'people.integer' => '人数は整数で入力してください',
            'people.min' => '人数は少なくてとも1人である必要があります',
        ];

    }
}
