<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'prefecture_id' => ['required', 'exists:prefectures,id'],
            'genre_id' => ['required', 'exists:genres,id'],
            'description' => ['required', 'string', 'max:500'],
            'image' => ['required_without:image_url', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'image_url' => ['prohibits:image', 'nullable', 'url'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '店名を入力してください',
            'name.string' => '店名を文字列で入力してください',
            'name.max' => '店名を191文字以内で入力してください',
            'prefecture_id.required' => '県名を選択してください',
            'prefecture_id.exists' => '指定された都道府県が存在しません',
            'genre_id.required' => 'ジャンルを選択してください',
            'genre_id.exists' => '指定されたジャンルが存在しません。',
            'description.required' => '概要を入力してください',
            'description.string' => '概要を文字列で入力してください',
            'description.max' => '概要を500文字以内で入力してください',
            'image.image' => '添付するファイルは画像ファイルである必要があります',
            'image.mimes' => '画像はjpeg,png,jpg,gifのいずれかの形式である必要があります',
            'image.max' => '画像のサイズは2MB以下である必要があります',
            'image_url.url' => '画像URLの形式が正しくありません',
            'image.required_without' => '画像ファイルか画像URLどちらかは必須です',
            'image_url.prohibits' => '画像ファイルか画像URLどちらかだけを入力してください',
        ];
    }
}
