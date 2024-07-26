<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
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
            'prefecture_id' => ['required'],
            'genre_id' => ['required'],
            'description' => ['required', 'string', 'max:500'],
            'image' => ['required', 'image', 'mines:jpeg,png,jpd,gif', 'max2048'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '店名を入力してください',
            'name.string' => '店名を文字列で入力してください',
            'name.max' => '店名を191文字以内で入力してください',
            'prefecture_id.required' => '県名を選択してください',
            'genre_id.required' => 'ジャンルを選択してください',
            'description.required' => '概要を入力してください',
            'description.string' => '概要を文字列で入力してください',
            'description.max' => '概要を500文字以内で入力してください',
            'image.required' => '写真を添付してください',
            'image.image' => '写真は画像ファイルである必要があります',
            'image.mines' => '写真はjpeg,png,jpg,gifのいずれかの形式である必要があります',
            'image.max' => '写真のサイズは2MB以下である必要があります',
        ];
    }
}
