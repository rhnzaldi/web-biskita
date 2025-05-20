<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;

class petaInteraktif extends Controller
{
    //
    

    public function petaInteraktif()
    {
        $wisatas = Wisata::all();
        return view('peta', compact('wisatas'));
    }

}
