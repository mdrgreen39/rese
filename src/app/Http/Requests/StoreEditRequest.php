<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ImageOrUrl;

class StoreEditRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:191'],
            'prefecture_id' => ['nullable', 'exists:prefectures,id'],
            'genre_id' => ['nullable', 'exists:genres,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'image_url' => ['nullable', 'url', new ImageOrUrl()],
        ];
    }

    public function messages()
    {
        return [
            'name.string' => '店名を文字列で入力してください',
            'name.max' => '店名を191文字以内で入力してください',
            'prefecture_id.exists' => '指定された都道府県が存在しません',
            'genre_id.exists' => '指定されたジャンルが存在しません。',
            'description.string' => '概要を文字列で入力してください',
            'description.max' => '概要を500文字以内で入力してください',
            'image.image' => '添付するファイルは画像ファイルである必要があります',
            'image.mimes' => '画像はjpeg,png,jpg,gifのいずれかの形式である必要があります',
            'image.max' => '画像のサイズは2MB以下である必要があります',
            'image_url.url' => '画像URLの形式が正しくありません',
        ];
    }
}
