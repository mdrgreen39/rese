<?php

namespace App\Livewire;

use Livewire\Component;

class FavoriteToggle extends Component
{
    public $shop;
    public $isFavorited;

    public function mount($shop)
    {
        $this->shop = $shop;
        $this->updateFavoriteStatus();
    }

    public function toggleFavorite()
    {
        if (auth()->check()) {
            auth()->user()->favorites()->toggle($this->shop->id);
            $this->isFavorited = auth()->user()->favorites->contains($this->shop->id);

        } else {
            $message = 'お気に入りに追加するには会員登録が必要です。<br>すでに会員登録済みの方は<a href="' . route('login') . '">こちら</a>からログインしてください。';
            return redirect()->route('register')->with('message', $message);
        }
    }

    private function updateFavoriteStatus()
    {
        $this->isFavorited = auth()->check() && auth()->user()->favorites()->where('shop_id', $this->shop->id)->exists();
    }

    public function render()
    {
        return view('livewire.favorite-toggle', [
            'isFavorite' => $this->isFavorited,
        ]);
    }
}
