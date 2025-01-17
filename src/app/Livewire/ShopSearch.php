<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Prefecture;
use App\Models\Genre;
use App\Models\Shop;

class ShopSearch extends Component
{
    public $prefectureId = '';
    public $genreId = '';
    public $searchTerm = '';
    public $sortBy = 'high_rating';

    protected $rules = [
        'searchTerm' => 'nullable|string',
        'prefectureId' => 'nullable|exists:prefectures,id',
        'genreId' => 'nullable|exists:genres,id',
    ];

    public function resetFilters()
    {
        $this->reset(['prefectureId', 'genreId', 'searchTerm']);

        $this->dispatch('searchUpdated', [
            'searchTerm' => '',
            'prefectureId' => '',
            'genreId' => '',
            'sortBy' => 'high_rating',
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        $this->dispatch('searchUpdated', [
            'searchTerm' => $this->searchTerm,
            'prefectureId' => $this->prefectureId,
            'genreId' => $this->genreId,
            'sortBy' => $this->sortBy,
        ]);
    }

    public function updateSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
        $this->applySort();
    }

    public function applySort()
    {
        $query = Shop::query();

        if ($this->prefectureId) {
            $query->where('prefecture_id', $this->prefectureId);
        }
        if ($this->genreId) {
            $query->where('genre_id', $this->genreId);
        }
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->sortBy === 'random') {
            $this->shops = $query->inRandomOrder()->get();
        } elseif ($this->sortBy === 'high_rating') {
            $this->shops = $query->with('comments')
            ->get()
                ->sortByDesc(function ($shop) {
                    return $shop->comments->avg('rating') ?? 0;
                });
        } elseif ($this->sortBy === 'low_rating') {
            $this->shops = $query->with('comments')
            ->get()
                ->sortBy(function ($shop) {
                    return $shop->comments->avg('rating') ?? PHP_INT_MAX;
                });
        }
        $this->dispatch('searchUpdated', [
            'searchTerm' => $this->searchTerm,
            'prefectureId' => $this->prefectureId,
            'genreId' => $this->genreId,
            'sortBy' => $this->sortBy,
        ]);
    }

    public function render()
    {
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('livewire.shop-search', [
            'prefectures' => $prefectures,
            'genres' => $genres,
            'sortBy' => $this->sortBy,
        ]);
    }
}
