<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Comment;


class CommentForm extends Component
{
    use WithFileUploads;

    public $rating = 0; // 星評価
    public $comment = ''; // コメント内容
    public $image; // 画像ファイル

    public function setRating($value)
    {
        $this->rating = $value;
    }

    public function submit()
    {
        // バリデーション
        $this->validate([
            'comment' => ['required', 'string', 'max:400'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'image' => ['nullable', 'mimes:jpeg,png', 'max:2048'],
        ], $this->messages());

        // 画像保存処理
        $imagePath = null;
        if ($this->image) {
            // 環境に応じて保存先を変更
            if (app()->environment('production')) {
                // 本番環境ではS3に保存
                $imagePath = $this->image->store('comments', 's3');
            } else {
                // 開発環境ではpublicディスクに保存
                $imagePath = $this->image->store('comments', 'public');
            }
        }

        // コメントデータベース保存
        Comment::create([
            'user_id' => auth()->id(),
            'shop_id' => $this->shopId,
            'comment' => $this->comment,
            'rating' => $this->rating,
            'image' => $imagePath,  // 保存した画像のパス
        ]);

        session()->flash('success', '口コミを投稿しました！');

        // 初期化
        $this->reset(['rating', 'comment', 'image']);
    }

    public function render()
    {
        return view('livewire.comment-form');
    }

    public function messages()
    {
        return [
            'comment.required' => 'コメントは必須です',
            'comment.string' => 'コメントは文字列で入力してください',
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
