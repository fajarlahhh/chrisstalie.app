<?php

namespace App\Livewire\Jurnalkeuangan;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Jurnal;

class Form extends Component
{
    #[Url]
    public $jenis;
    public $data;

    public function mount(Jurnal $data)
    {
        if ($data->exists) {
            $this->jenis = strtolower(str_replace(' ', '', $data->jenis));
        }
        $this->data = $data;
    }
    public function render()
    {
        return view('livewire.jurnalkeuangan.form');
    }
}
