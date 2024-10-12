<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ImageOrUrl implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = request()->all();

        // 画像ファイルと画像URLが同時に指定されている場合
        if (isset($data['image']) && isset($data['image_url'])) {
            $fail('画像ファイルと画像URLは同時に指定できません');
        }
    }
}
