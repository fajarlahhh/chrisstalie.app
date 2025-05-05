<?php

namespace App\Livewire\Pelayanan\Catatanpasien;

use App\Models\Pendaftaran;
use Livewire\Component;

class Form extends Component
{
    public $data, $note;

    public function mount(Pendaftaran $data)
    {
        $this->data = $data;
        $this->note = $data->note;
    }

    public function submit()
    {
        $this->validate([
            'note' => 'required',
        ]);

        $this->data->note = $this->note;
        $this->data->save();

        $this->redirect('/pelayanan/catatanpasien');
    }

    public function render()
    {
        return view('livewire.pelayanan.catatanpasien.form');
    }
}
