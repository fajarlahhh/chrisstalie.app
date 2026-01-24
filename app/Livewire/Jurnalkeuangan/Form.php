<?php

namespace App\Livewire\Jurnalkeuangan;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\JurnalKeuangan;

class Form extends Component
{
    #[Url]
    public $jenis;
    public $data;

    public function mount(JurnalKeuangan $data)
    {
        if ($data->exists) {
            $this->jenis = 'jurnalumum';
        }
        $this->data = $data;
    }
    
    public function render()
    {
        return view('livewire.jurnalkeuangan.form');
    }
}
