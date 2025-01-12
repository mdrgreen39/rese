<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class EditCommentImage extends Component
{
    use WithFileUploads;

    public $existingImage;
    public $imagePreview;
    public $image;

    // コンストラクタで既存の画像を設定
    public function mount($existingImage)
    {
        $this->existingImage = $existingImage;
    }

    // 画像アップロード処理
    public function uploadImage()
    {
        if ($this->image) {
            $this->image->store('public/comments');
        }
    }

    public function render()
    {
        return view('livewire.edit-comment-image');
    }
}
