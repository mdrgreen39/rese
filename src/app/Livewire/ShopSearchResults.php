<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;

class ShopSearchResults extends Component
{
    public $prefectureId;
    public $genreId;
    public $searchTerm;
    public $shops;
    public $sortBy = 'high_rating';

    protected $listeners = ['searchUpdated' => 'updateSearchResults'];

    public function mount()
    {
        $this->shops = Shop::all();

        $this->search();
    }

    public function updateSearchResults($params)
    {
        $this->prefectureId = $params['prefectureId'] ?? '';
        $this->genreId = $params['genreId'] ?? '';
        $this->searchTerm = $params['searchTerm'] ?? '';
        $this->sortBy = $params['sortBy'] ?? 'high_rating'; // 並び替え条件を取得

        $this->search();
    }
    

    public function search()
    {
        $this->shops = Shop::query()
            ->with('comments')
            ->when($this->prefectureId, function ($query) {
                return $query->where('prefecture_id', $this->prefectureId);
            })
            ->when($this->genreId, function ($query) {
                return $query->where('genre_id', $this->genreId);
            })
            ->when($this->searchTerm, function ($query) {
                return $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->get();


        // 各ショップにコメント数と平均評価を計算
        foreach ($this->shops as $shop) {
            $shop->average_rating = $shop->comments->isEmpty() ? null : $shop->comments->avg('rating');
        }
        $this->sortShops();
    }

    private function sortShops()
    {

        // 評価が高い順、低い順で並べ替え
        if ($this->sortBy === 'high_rating') {
            // 評価がないショップを最後尾に並べる
            $this->shops = $this->shops->sortByDesc(function ($shop) {
                return $shop->average_rating ?? -1; // 評価がない場合（コメントがない場合）、最後尾に
            });
        } elseif ($this->sortBy === 'low_rating') {
            // 評価がないショップを最後尾に並べる
            $this->shops = $this->shops->sortBy(function ($shop) {
                return $shop->average_rating ?? PHP_INT_MAX; // 評価がない場合（コメントがない場合）、最後尾に
            });
        } else {
            // ランダムで並べ替え
            $this->shops = $this->shops->shuffle();
        }
    }

    public function render()
    {
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('livewire.shop-search-results', [
            'shops' => $this->shops,
            'prefectures' => $prefectures,
            'genres' => $genres,
        ]);
    }
}
