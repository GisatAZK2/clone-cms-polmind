<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DaftarDosen extends Component
{
    public $daftardosen;

    public function __construct($daftardosen)
    {
        $this->daftardosen = $daftardosen;
    }

    public function render()
    {
        return view('components.daftar-dosen');
    }
}
