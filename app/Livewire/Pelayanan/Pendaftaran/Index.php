<?php

namespace App\Livewire\Pelayanan\Pendaftaran;

use App\Models\Pasien;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Nakes;
use App\Models\Registration;
use Livewire\WithPagination;
use App\Class\SatusehatClass;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;
    public $previous, $nakesData = [], $purchase, $pasien, $pasienData = [];
    public $date, $pasien_id, $rm, $uraian, $nakes_id, $nik, $nama, $alamat, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $no_telpon, $pasien_description;

    public function mount()
    {
        $this->pasienData = Pasien::orderBy('nama')->limit(10)->get()->toArray();
        $this->previous = url()->previous();
        $this->date = $this->date ?: date('Y-m-d');
        $this->nakesData = Nakes::dokter()->with('pegawai')->orderBy('nama')->get()->map(fn($q) => [
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
        $this->no_telpon = $this->pasien->no_telpon;
        $this->pasien_description = $this->pasien->pasien_description;
    }

    public function resetPasien()
    {
        $this->reset(['nik', 'rm', 'nama', 'alamat', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'no_telpon', 'pasien_description', 'pasien_id']);
    }

    public function submit()
    {
        if ($this->pasien_id) {
            $this->validate([
                'date' => 'required',
                'alamat' => 'required',
                'nakes_id' => 'required',
            ]);
        } else {
            $this->validate([
                'date' => 'required',
                'nakes_id' => 'required',
                'nik' => 'required|unique:pasiens,nik',
                'nama' => 'required',
                'alamat' => 'required',
                'jenis_kelamin' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'no_telpon' => 'required',
            ]);
        }
        DB::transaction(function () {
            $pasienSatuSehat = SatusehatClass::getPasienByNik($this->nik);
            if (!$this->pasien_id) {
                $pasien = new Pasien();
                $last = Pasien::where('created_at', 'like', date('Y-m') . '%')->orderBy('created_at', 'desc')->first();
                $pasien->rm = date('y.m.') . ($last ? sprintf('%04s', substr($last->rm, 6, 4) + 1) : '0001');
                $pasien->user_id = auth()->id();
            } else {
                $pasien = Pasien::find($this->pasien_id);
            }
            $pasien->nik = $this->nik;
            $pasien->nama = $this->nama;
            $pasien->alamat = $this->alamat;
            $pasien->jenis_kelamin = $this->jenis_kelamin;
            $pasien->tempat_lahir = $this->tempat_lahir;
            $pasien->tanggal_lahir = $this->tanggal_lahir;
            $pasien->no_telpon = $this->no_telpon;
            $pasien->tanggal_registrasi = $this->date;
            $pasien->ihs = $pasienSatuSehat ? $pasienSatuSehat['id'] : null;
            $pasien->save();

            $datetime = $this->date . date(' H:i:s');
            $data = new Registration();
            if (!$this->pasien_id) {
                $data->new = 1;
            }
            $data->datetime = $datetime;
            $data->order = str_replace(['/', ':', '-', ' '], '', $datetime);
            $data->uraian = $this->uraian;
            $data->nakes_id = $this->nakes_id;
            $data->pasien_id = $this->pasien_id ? $this->pasien_id : $pasien->id;
            $data->user_id = auth()->id();
            $data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pelayanan/pendaftaran');
    }

    public function render()
    {
        return view('livewire.pelayanan.pendaftaran.index');
    }
}
