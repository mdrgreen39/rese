<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Shop;

class FavoriteToggle extends Component
{
    public $shop;
    public $isFavorited;

    public function mount($shop)
    {

        $this->shop = $shop;

        // ログインしている場合にのみ、お気に入りの状態を確認
        if (Auth::check()) {
            $this->isFavorited = Auth::user()->favorites()->where('shop_id', $this->shop->id)->exists();
        }
    }

    public function toggleFavorite()
    {
        if (auth()->check()) {
            // ユーザーが認証されている場合、お気に入りをトグルする
            auth()->user()->favorites()->toggle($this->shop->id);
            $this->isFavorited = auth()->user()->favorites->contains($this->shop->id);
        } else {
            // 認証されていない場合、ログイン画面にリダイレクトする
            $message = 'お気に入りに追加するには会員登録が必要です。<br>すでに会員登録済みの方は<a href="' . route('login') . '">こちら</a>からログインしてください。';
            return redirect()->route('register')->with('message', $message);
        }
    }

    public function render()
    {
        

        return view('livewire.favorite-toggle', [
            'isFavorite' => auth()->check() ? auth()->user()->favorites->contains($this->shop->id) : false,
        ]);
    }
}
