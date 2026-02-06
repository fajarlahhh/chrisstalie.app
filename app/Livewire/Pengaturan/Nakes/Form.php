<?php

namespace App\Livewire\Pengaturan\Nakes;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\KepegawaianPegawai;
use App\Models\Nakes;
use App\Traits\CustomValidationTrait;
use App\Models\KodeAkun;

class Form extends Component
{
    use CustomValidationTrait;
    public $data, $dataPegawai = [], $kepegawaianPegawai, $dataKodeAkun = [];
    public $nama, $ihs, $nik, $alamat, $no_hp, $dokter = false, $perawat = false, $kepegawaian_pegawai_id, $kode_akun_jasa_dokter_id, $kode_akun_jasa_perawat_id;

    public function updatedPegawaiId($value)
    {
        $this->reset('nama', 'ihs', 'nik', 'alamat', 'no_hp', 'dokter');
        if ($value) {
            $this->kepegawaianPegawai = KepegawaianPegawai::find($this->kepegawaian_pegawai_id);
            $this->nama = $this->kepegawaianPegawai->nama;
            $this->ihs = $this->kepegawaianPegawai->ihs;
            $this->nik = $this->kepegawaianPegawai->nik;
            $this->alamat = $this->kepegawaianPegawai->alamat;
            $this->no_hp = $this->kepegawaianPegawai->no_hp;
        } else {
            $this->kepegawaianPegawai = null;
        }
    }

    public function submit()
    {
        if (!$this->kepegawaian_pegawai_id) {
            $this->validateWithCustomMessages([
                'nama' => 'required',
                'nik' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required',
                'kode_akun_jasa_dokter_id' => 'required_if:dokter,1',
                'kode_akun_jasa_perawat_id' => 'required',
            ]);
        }
        $this->validateWithCustomMessages([
            'kode_akun_jasa_dokter_id' => 'required_if:dokter,true',
            'kode_akun_jasa_perawat_id' => 'required_if:perawat,true',
        ]);

        DB::transaction(function () {
            $this->data->kepegawaian_pegawai_id = $this->kepegawaian_pegawai_id;
            $this->data->ihs = $this->ihs;
            if (!$this->kepegawaian_pegawai_id) {
                $this->data->nama = $this->nama;
                $this->data->nik = $this->nik;
                $this->data->alamat = $this->alamat;
                $this->data->no_hp = $this->no_hp;
            }
            $this->data->dokter = $this->dokter ? 1 : 0;
            $this->data->perawat = $this->perawat ? 1 : 0;
            $this->data->kode_akun_jasa_dokter_id = $this->dokter ? $this->kode_akun_jasa_dokter_id : null;
            $this->data->kode_akun_jasa_perawat_id = $this->perawat ? $this->kode_akun_jasa_perawat_id : null;
            $this->data->pengguna_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pengaturan/nakes');
    }

    public function mount(Nakes $data)
    {   
        $this->dataKodeAkun = KodeAkun::detail()->where('kategori', 'Kewajiban')->get()->toArray();
        $this->dataPegawai = KepegawaianPegawai::orderBy('nama')->get()->toArray();
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->dokter = $this->data->dokter == 1 ? true : false;
        $this->perawat = $this->data->perawat == 1 ? true : false;
    }

    public function render()
    {
        return view('livewire.pengaturan.nakes.form');
    }
}
