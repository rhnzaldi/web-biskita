<?php

namespace App\Livewire\Company;

use App\Models\Artikel;
use Livewire\Component;

class ShowArtikels extends Component
{
    public function render()
    {
        $artikels = Artikel::all();
        return view('livewire.company.show-artikels', compact('artikels'));
    }
}
