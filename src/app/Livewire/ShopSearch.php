<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Prefecture;
use App\Models\Genre;

class ShopSearch extends Component
{
    public $prefectureId = '';
    public $genreId = '';
    public $searchTerm = '';

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
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        $this->dispatch('searchUpdated', [
            'searchTerm' => $this->searchTerm,
            'prefectureId' => $this->prefectureId,
            'genreId' => $this->genreId,
        ]);
    }

    public function render()
    {
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('livewire.shop-search', [
            'prefectures' => $prefectures,
            'genres' => $genres,
        ]);
    }
}
