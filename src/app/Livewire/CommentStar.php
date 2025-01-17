<?php

namespace App\Livewire;

use Livewire\Component;

class CommentStar extends Component
{
    public $rating = 0;
    public $isEditing = false;

    public function mount($rating = 0, $isEditing = false)
    {
        $this->rating = $rating;
        $this->isEditing = $isEditing;
    }


    public function setRating($value)
    {
        $this->rating = $value;

        $this->dispatch('ratingUpdated', $value);
    }

    public function render()
    {
        return view('livewire.comment-star');
    }
}
