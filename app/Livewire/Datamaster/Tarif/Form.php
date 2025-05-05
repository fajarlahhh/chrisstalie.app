<?php

namespace App\Livewire\Datamaster\Tarif;

use Livewire\Component;
use App\Models\Tarif;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $modal;
    public $nama, $porsi_nakes, $porsi_kantor, $kategori, $keuntungan, $porsi_petugas, $deskripsi, $icd_9_cm;

    public function submit()
    {
        $this->validate([
            'kategori' => 'required',
            'nama' => 'required',
            'modal' => 'required|numeric',
            'porsi_petugas' => 'required|numeric',
            'porsi_nakes' => 'required|numeric',
            'porsi_kantor' => 'required|numeric',
        ]);

        DB::transaction(function () {
            $this->data->nama = $this->nama;
            $this->data->kategori = $this->kategori;
            $this->data->deskripsi = $this->deskripsi;
            $this->data->biaya = $this->modal + $this->porsi_petugas + $this->porsi_kantor + $this->porsi_nakes;
            $this->data->modal = $this->modal;
            $this->data->porsi_petugas = $this->porsi_petugas;
            $this->data->porsi_nakes = $this->porsi_nakes;
            $this->data->porsi_kantor = $this->porsi_kantor;
            $this->data->icd_9_cm = $this->icd_9_cm;
            $this->data->user_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(Tarif $data)
    {
        $this->previous = url()->previous();
        $this->data = $data;
        $this->fill($this->data->toArray());
    }

    public function render()
    {
        return view('livewire.datamaster.tarif.form');
    }
}
