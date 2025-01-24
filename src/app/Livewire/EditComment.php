<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Comment;

class EditComment extends Component
{
    use WithFileUploads;

    public $shop;
    public $rating;
    public $comment;
    public $image;
    public $existingImage;
    public $imagePreview = null;


    public function mount($shop, $rating = 0, $comment = null, $existingImage = null)
    {
        $this->shop = $shop;
        $this->rating = $rating;
        $this->comment = $comment ? $comment->comment : '';
        $this->existingImage = $existingImage;

        logger()->info('Existing Image:', [$this->existingImage]);
    

    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function updatedImage()
    {
        $this->existingImage = null;
    }

    public function updateComment()
    {

        $this->validate([
            'comment' => ['required', 'string', 'max:400'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'image' => ['nullable', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], $this->messages());

        $imagePath = $this->existingImage;

        $imagePath = $this->existingImage;

        if ($this->image) {
            if (app()->environment('production')) {
                $imagePath = $this->image->store('comments', 's3');
            } else {
                $imagePath = $this->image->store('comments', 'public');
            }
        }

        Comment::where('shop_id', $this->shop->id)
            ->where('user_id', auth()->id())
            ->update([
                'rating' => $this->rating,
                'comment' => $this->comment,
                'image' => $imagePath,
            ]);

        return redirect()->route('comment.update.success', ['shop_id' => $this->shop->id]);
    }

    public function render()
    {
        return view('livewire.edit-comment');
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
