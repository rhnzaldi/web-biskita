<?php

namespace App\Livewire\Company;

use App\Models\Wisata;
use Livewire\Component;

class ShowWisatas extends Component
{
    public function tampilkanDiPeta($lat, $lng, $nama)
    {
        $this->dispatch('show-on-map', [
            'lat' => $lat,
            'lng' => $lng,
            'nama' => $nama,
        ]);
    }

    public function render()
    {
        $wisatas = Wisata::all();
        return view('livewire.company.show-wisatas', compact('wisatas'));
    }
}
