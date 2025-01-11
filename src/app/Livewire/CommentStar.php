<?php

namespace App\Livewire;

use Livewire\Component;

class CommentStar extends Component
{
    public $rating = 0;

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
