<?php

namespace App\Livewire\Informasi\Pasien;

use Livewire\Component;
use App\Models\Pasien;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $noRm;

    public $dataPasien;
    public $rekamMedis;

    public function updatedNoRm()
    {
        $this->dataPasien = $this->getRekamMedis($this->noRm);
    }

    private function getRekamMedis($id)
    {        
        return Pasien::with(
            'rekamMedis.nakes',
            'rekamMedis.pengguna.kepegawaianPegawai',
            'rekamMedis.pemeriksaanAwal.pengguna',
            'rekamMedis.diagnosis.pengguna.kepegawaianPegawai',
            'rekamMedis.tindakan.pengguna.kepegawaianPegawai',
            'rekamMedis.tindakan.tarifTindakan',
            'rekamMedis.tindakan.dokter.kepegawaianPegawai',
            'rekamMedis.tindakan.perawat.kepegawaianPegawai',
            'rekamMedis.tindakan.barangSatuan',
            'rekamMedis.tindakan.barangSatuan.barang',
            'rekamMedis.siteMarking.pengguna',
            'rekamMedis.resepObat.pengguna.kepegawaianPegawai',
            'rekamMedis.resepObat.barangSatuan',
            'rekamMedis.resepObat.barangSatuan.barang',
            'rekamMedis.resepObat.barangSatuan.barang.kodeAkun',
            'rekamMedis.pembayaran.pengguna'
        )->find($id);
    }

    public function mount()
    {
        if ($this->noRm) {
            $this->dataPasien = $this->getRekamMedis($this->noRm);
        } else {
            $this->dataPasien = null;
        }
    }

    public function render()
    {
        return view('livewire.informasi.pasien.index');
    }
}
