<?php

namespace App\Livewire\Datamaster\Barang;

use App\Models\Barang;
use Livewire\Component;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $supplierData = [];
    public $nama, $satuan, $stok_minimum, $harga, $jenis, $deskripsi, $konsinyator_id, $precompounded, $kfa, $porsi_kantor, $porsi_nakes, $modal;

    public function submit()
    {
        $this->validate([
            'nama' => 'required',
            'satuan' => 'required',
            'stok_minimum' => 'required',
            'harga' => 'required',
            'konsinyator_id' => 'integer|nullable',
            'jenis' => 'required',
        ]);

        if ($this->konsinyator_id) {
            $this->validate([
                'modal' => 'required',
                'porsi_nakes' => 'required',
                'porsi_kantor' => 'required',
            ]);
        }

        DB::transaction(function () {
            $this->data->nama = $this->nama;
            $this->data->satuan = $this->satuan;
            $this->data->stok_minimum = $this->stok_minimum;
            $this->data->harga = $this->harga;
            $this->data->deskripsi = $this->deskripsi;
            $this->data->precompounded = $this->precompounded;
            $this->data->jenis = $this->jenis;
            $this->data->konsinyator_id = $this->konsinyator_id;
            $this->data->modal = $this->konsinyator_id ? $this->modal : null;
            $this->data->porsi_kantor = $this->konsinyator_id ? $this->porsi_kantor : 100;
            $this->data->porsi_nakes = $this->konsinyator_id ? $this->porsi_nakes : 0;
            $this->data->kfa = $this->kfa;
            $this->data->pengguna_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(Barang $data)
    {
        $this->previous = url()->previous();
        $this->supplierData = Supplier::withoutGlobalScopes()->orderBy('nama')->where('konsinyator', 1)->get()->toArray();
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->precompounded = $this->data->precompounded == 1 ? true : false;
    }

    public function render()
    {
        return view('livewire.datamaster.barang.form');
    }
}
