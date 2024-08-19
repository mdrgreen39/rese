<?php

namespace App\Livewire;

use Livewire\Component;

class ReservationForm extends Component
{
    public $shop;
    public $times = [];
    public $numberOfPeople = [];
    public $date = '';
    public $time = '';
    public $people = '';

    public function mount($shop, $times, $numberOfPeople)
    {
        $this->shop = $shop;
        $this->times = $times;
        $this->time =  '';
        $this->numberOfPeople = $numberOfPeople;

    }

    public function updatedPeople($value)
    {
        $this->people = $value;
    }

    public function render()
    {

        return view('livewire.reservation-form');
    }
}
