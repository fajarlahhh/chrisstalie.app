<?php

namespace App\Livewire\Informasipasien;

use App\Models\Pasien;
use Livewire\Component;

class Index extends Component
{
    public $pasien_id, $pasien;

    public function updatedPasienId($value)
    {
        $this->pasien = Pasien::where('id', $value)->first();
    }

    public function render()
    {
        return view('livewire.informasipasien.index');
    }
}
