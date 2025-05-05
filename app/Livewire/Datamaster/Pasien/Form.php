<?php

namespace App\Livewire\Datamaster\Pasien;

use App\Models\Pasien;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous;
    public $nama, $ihs, $nik, $rm, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $tanggal_registrasi, $uraian, $alamat, $no_telpon, $dokter = false;

    public function submit()
    {
        $this->validate([
            'nama' => 'required',
            'nik' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'no_telpon' => 'required',
        ]);

        DB::transaction(function () {
            $this->data->nama = $this->nama;
            $this->data->ihs = $this->ihs;
            $this->data->nik = $this->nik;
            $this->data->uraian = $this->uraian;
            $this->data->jenis_kelamin = $this->jenis_kelamin;
            $this->data->alamat = $this->alamat;
            $this->data->no_telpon = $this->no_telpon;
            $this->data->dokter = $this->dokter ? 1 : 0;
            $this->data->user_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(Pasien $data)
    {
        $this->previous = url()->previous();
        $this->data = $data;
        $this->fill($this->data->toArray());
    }
    
    public function render()
    {
        return view('livewire.datamaster.pasien.form');
    }
}
