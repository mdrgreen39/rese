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
        $this->sortBy = $params['sortBy'] ?? 'high_rating';

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

        foreach ($this->shops as $shop) {
            $shop->average_rating = $shop->comments->isEmpty() ? null : $shop->comments->avg('rating');
        }
        $this->sortShops();
    }

    private function sortShops()
    {

        if ($this->sortBy === 'high_rating') {
            $this->shops = $this->shops->sortByDesc(function ($shop) {
                return $shop->average_rating ?? -1;
            });
        } elseif ($this->sortBy === 'low_rating') {
            $this->shops = $this->shops->sortBy(function ($shop) {
                return $shop->average_rating ?? PHP_INT_MAX;
            });
        } else {
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
