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

    protected $listeners = ['searchUpdated' => 'updateSearchResults'];

    public function updateSearchResults($params)
    {
        $this->prefectureId = $params['prefectureId'] ?? '';
        $this->genreId = $params['genreId'] ?? '';
        $this->searchTerm = $params['searchTerm'] ?? '';
    }


    public function render()
    {
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        $shops = Shop::query()
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

        // dd('Fetched shops', $shops);

        return view('livewire.shop-search-results', [
            'shops' => $shops,
            'prefectures' => $prefectures,
            'genres' => $genres,
        ]);
    }
}
