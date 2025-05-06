<?php

namespace App\Livewire\Pelayanan\Pendaftaran;

use App\Models\Nakes;
use App\Models\Pasien;
use Livewire\Component;
use App\Class\PasienClass;
use App\Models\Pendaftaran;
use Livewire\WithPagination;
use App\Class\PelayananClass;
use App\Class\SatusehatClass;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    use WithPagination;
    public $previous, $dataNakes = [], $pasien, $data;
    public $tanggal, $pasien_id, $rm, $catatan, $nakes_id, $nik, $nama, $alamat, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $no_hp;

    public function mount(Pendaftaran $data)
    {
        $this->data = $data;
        if ($this->data->exists) {
            $this->fill($this->data->toArray());
            $this->fill($this->data->pasien->toArray());
        }
        $this->previous = url()->previous();
        $this->tanggal = $this->tanggal ?: date('Y-m-d');
        $this->dataNakes = Nakes::dokter()->with('pegawai')->orderBy('nama')->get()->map(fn($q) => [
            'id' => $q->id,
            'nama' => $q->nama ?: $q->pegawai->nama,
            'dokter' => $q->dokter == 1 ? 'Dokter' : '',
        ])->toArray();
    }

    public function updatedPasienId($id)
    {
        $this->pasien_id = $id;
        $this->pasien = Pasien::find($id);
        $this->rm = $this->pasien->rm;
        $this->nik = $this->pasien->nik;
        $this->nama = $this->pasien->nama;
        $this->alamat = $this->pasien->alamat;
        $this->jenis_kelamin = $this->pasien->jenis_kelamin;
        $this->tempat_lahir = $this->pasien->tempat_lahir;
        $this->tanggal_lahir = $this->pasien->tanggal_lahir;
        $this->no_hp = $this->pasien->no_hp;
    }

    public function resetPasien()
    {
        $this->reset(['nik', 'rm', 'nama', 'alamat', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'no_hp', 'catatan', 'pasien_id']);
    }

    public function submit()
    {
        if ($this->pasien_id) {
            $this->validate([
                'tanggal' => 'required',
                'alamat' => 'required',
                'nakes_id' => 'required',
            ]);
        } else {
            $this->validate([
                'tanggal' => 'required',
                'nakes_id' => 'required',
                'nik' => 'required|unique:pasien,nik',
                'nama' => 'required',
                'alamat' => 'required',
                'jenis_kelamin' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'no_hp' => 'required',
            ]);
        }
        DB::transaction(function () {
            if (!$this->data->exists) {
                if (!$this->pasien_id) {
                    $pasienSatuSehat = SatusehatClass::getPasienByNik($this->nik);
                    $pasien = new Pasien();
                    $pasien->rm = PasienClass::getNoRm();
                    $pasien->nik = $this->nik;
                    $pasien->nama = $this->nama;
                    $pasien->alamat = $this->alamat;
                    $pasien->jenis_kelamin = $this->jenis_kelamin;
                    $pasien->tempat_lahir = $this->tempat_lahir;
                    $pasien->tanggal_lahir = $this->tanggal_lahir;
                    $pasien->no_hp = $this->no_hp;
                    $pasien->tanggal_daftar = $this->tanggal;
                    $pasien->ihs = $pasienSatuSehat ? $pasienSatuSehat['id'] : null;
                    $pasien->pengguna_id = auth()->id();
                    $pasien->save();
                }

                $this->data->baru = 1;
                $this->data->nomor = PelayananClass::getNomor($this->tanggal);
                $this->data->pasien_id = $this->pasien_id ? $this->pasien_id : $pasien->id;
            }

            $this->data->tanggal = $this->tanggal;
            $this->data->catatan = $this->catatan;
            $this->data->nakes_id = $this->nakes_id;
            $this->data->pengguna_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pelayanan/pendaftaran');
    }

    public function render()
    {
        return view('livewire.pelayanan.pendaftaran.form');
    }
}
