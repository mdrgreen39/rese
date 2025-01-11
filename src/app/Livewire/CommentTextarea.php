<?php

namespace App\Livewire;

use Livewire\Component;

class CommentTextarea extends Component
{
    public $comment = '';

    public function updatedComment($value)
    {
        $this->dispatch('commentUpdated', $value);
    }

    public function render()
    {
        return view('livewire.comment-textarea');
    }
}
