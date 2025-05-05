<?php

namespace App\Livewire\Datamaster\Nakes;

use App\Class\SatusehatClass;
use App\Models\Pegawai;
use Livewire\Component;
use App\Models\Nakes;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $pegawaiData = [];
    public $nama, $ihs, $nik, $jenis_kelamin, $deskripsi, $alamat, $no_hp, $dokter = false, $pegawai_id;

    public function submit()
    {
        if (!$this->pegawai_id) {
            $this->validate([
                'nama' => 'required',
                'nik' => 'required',
                'jenis_kelamin' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required',
            ]);
        }

        DB::transaction(function () {
            if ($this->pegawai_id) {
                $nakesSatuSehat = SatusehatClass::getNakesByNik(Pegawai::find($this->pegawai_id)->nik);
            } else {
                $nakesSatuSehat = SatusehatClass::getNakesByNik($this->nik);
            }
            $this->data->pegawai_id = $this->pegawai_id ?: null;
            $this->data->nama = $this->nama;
            $this->data->ihs = $this->ihs;
            $this->data->nik = $this->nik;
            $this->data->deskripsi = $this->deskripsi;
            $this->data->jenis_kelamin = $this->jenis_kelamin;
            $this->data->alamat = $this->alamat;
            $this->data->no_hp = $this->no_hp;
            $this->data->dokter = $this->dokter ? 1 : 0;
            $this->data->user_id = auth()->id();
            $this->data->ihs = $nakesSatuSehat ? ($nakesSatuSehat['entry'] ? $nakesSatuSehat['entry']['0']['resource']['id'] : null)  : null;
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(Nakes $data)
    {
        $this->previous = url()->previous();
        $this->pegawaiData = Pegawai::orderBy('nama')->with('pengguna')->get()->toArray();
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->dokter = $this->data->dokter == 1 ? true : false;
    }

    public function render()
    {
        return view('livewire.datamaster.nakes.form');
    }
}
