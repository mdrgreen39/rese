<?php

namespace App\Livewire;

use Illuminate\Http\UploadedFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Comment;

class CommentForm extends Component
{
    use WithFileUploads;

    public $shop;
    public $prefecture;
    public $genre;
    public $rating = 0; // 星評価
    public $comment = ''; // コメント内容
    public $image; // 画像ファイル

    protected $listeners = [
        'ratingUpdated' => 'updateRating',
        'commentUpdated' => 'updateComment',
        'imageUpdated' => 'updateImage'
    ];


    public function mount($shop, $prefecture, $genre, $rating = 0, $comment = '', $image = null)
    {
        $this->shop = $shop;
        $this->prefecture = $prefecture;
        $this->genre = $genre;
        $this->rating = $rating ?? 0;
        $this->comment = $comment;
        $this->image = $image;
    }

    // 評価が更新された時の処理
    public function updateRating($value)
    {
        $this->rating = $value;
    }

    public function updateComment($value)
    {
        $this->comment = $value;
    }

    public function updateImage($image)
    {
        $this->image = $image;
    }

    public function submit()
    {
        $commentCount = Comment::where('user_id', auth()->id()) // 現在のユーザー
            ->where('shop_id', $this->shop->id) // 現在の店舗
            ->count(); // 投稿数をカウント

        // もし投稿数が2回以上であれば、エラーメッセージを返す
        if ($commentCount >= 2) {
            session()->flash('error', '同じ店舗に2回以上コメントを投稿することはできません');
            return;
        }

        if (is_string($this->image)) {
            $this->image = new UploadedFile(
                $this->image,
                basename($this->image),
                mime_content_type($this->image),
                null,
                true
            );

        }

        $this->validate([
            'comment' => ['required', 'string', 'max:400'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'image' => ['nullable', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], $this->messages());


        $imagePath = null;
        if ($this->image) {
            if (app()->environment('production')) {
                $imagePath = $this->image->store('comments', 's3');
            } else {
                $imagePath = $this->image->store('comments', 'public');
            }
        }

        Comment::create([
            'user_id' => auth()->id(),
            'shop_id' => $this->shop->id,
            'comment' => $this->comment,
            'rating' => $this->rating,
            'image' => $imagePath,
        ]);

        return redirect()->route('comment.success', ['shop_id' => $this->shop->id]);


    }

    public function render()
    {
        $this->dispatch('parent-component', 'updateRating', $this->rating);
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
