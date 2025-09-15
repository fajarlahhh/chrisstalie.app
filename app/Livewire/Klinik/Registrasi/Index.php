<?php

namespace App\Livewire\Klinik\Registrasi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pasien;
use App\Models\Nakes;
use App\Models\Registrasi;
use Illuminate\Support\Facades\DB;
use App\Helpers\SatusehatClass;

class Index extends Component
{
    use WithPagination;
    public $previous, $dataNakes = [], $purchase, $pasien;
    public $tanggal, $pasien_id, $rm, $catatan, $nakes_id, $nik, $nama, $alamat, $jenis_kelamin, $tanggal_lahir, $no_hp, $pasien_description;

    public function mount()
    {
        $this->previous = url()->previous();
        $this->tanggal = $this->tanggal ?: date('Y-m-d');
        $this->dataNakes = Nakes::dokter()->with('pegawai')->orderBy('nama')->get()->map(fn($q) => [
            'id' => $q->id,
            'nama' => $q->nama ?: $q->pegawai->nama,
        ])->toArray();
    }

    public function updatedPasienId($id)
    {
        $this->pasien_id = $id;
        $this->pasien = Pasien::find($id);
        $this->rm = $this->pasien->id;
        $this->nik = $this->pasien->nik;
        $this->nama = $this->pasien->nama;
        $this->alamat = $this->pasien->alamat;
        $this->jenis_kelamin = $this->pasien->jenis_kelamin;
        $this->tanggal_lahir = $this->pasien->tanggal_lahir->format('Y-m-d');
        $this->no_hp = $this->pasien->no_hp;
    }

    public function resetPatient()
    {
        $this->reset(['nik', 'rm', 'nama', 'alamat', 'jenis_kelamin', 'tanggal_lahir', 'no_hp', 'catatan', 'pasien_id']);
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
                'tanggal_lahir' => 'required',
                'no_hp' => 'required',
            ]);
        }
        DB::transaction(function () {
            if (!$this->pasien_id) {
                $pasien = new Pasien();
                $last = Pasien::where('created_at', 'like', date('Y-m') . '%')->orderBy('created_at', 'desc')->first();
                $pasien->id = date('y.m.') . ($last ? sprintf('%04s', substr($last->rm, 6, 4) + 1) : '0001');
                $pasien->pengguna_id = auth()->id();
            } else {
                $pasien = Pasien::find($this->pasien_id);
            }
            $pasien->nik = $this->nik;
            $pasien->nama = $this->nama;
            $pasien->alamat = $this->alamat;
            $pasien->jenis_kelamin = $this->jenis_kelamin;
            $pasien->tanggal_lahir = $this->tanggal_lahir;
            $pasien->no_hp = $this->no_hp;
            $pasien->tanggal_daftar = $this->tanggal;
            $pasien->save();

            $data = new Registrasi();
            if (!$this->pasien_id) {
                $data->baru = 1;
            }
            $data->tanggal = $this->tanggal;
            $data->urutan = str_replace(['/', ':', '-', ' '], '', $this->tanggal. date(' H:i:s'));
            $data->catatan = $this->catatan;
            $data->nakes_id = $this->nakes_id;
            $data->pasien_id = $this->pasien_id ? $this->pasien_id : $pasien->id;
            $data->pengguna_id = auth()->id();
            $data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/klinik/registrasi');
    }
    
    public function render()
    {
        return view('livewire.klinik.registrasi.index');
    }
}
