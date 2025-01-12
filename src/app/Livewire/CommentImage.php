<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class CommentImage extends Component
{
    use WithFileUploads;

    public $imagePreview = null;
    public $image;

    public function updatedImage()
    {
        if ($this->image) {
            $this->dispatch('imageUpdated', $this->image->getRealPath()); // パスを送信
        }
    }

    public function render()
    {
        return view('livewire.comment-image');
    }
}
