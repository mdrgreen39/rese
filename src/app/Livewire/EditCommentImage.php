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

    public function mount($existingImage)
    {
        $this->existingImage = $existingImage;
    }

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
