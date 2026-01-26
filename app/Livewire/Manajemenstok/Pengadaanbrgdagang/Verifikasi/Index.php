<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Verifikasi;

use Livewire\Component;
use App\Models\VerifikasiPengadaan;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\PermintaanPengadaan;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $status = 'Pending';

    public function updated()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.manajemenstok.pengadaanbrgdagang.verifikasi.index', [
            'data' => PermintaanPengadaan::with([
                'pengguna.kepegawaianPegawai',
                'verifikasiPengadaan.pengguna.kepegawaianPegawai',
                'permintaanPengadaanDetail.barangSatuan.satuanKonversi',
                'permintaanPengadaanDetail.barangSatuan.barang',
            ])->with(['verifikasiPengadaan' => fn($q) => $q->whereNotNull('status')])
                ->when($this->status == 'Pending', fn($q) => $q->whereHas('verifikasiPengadaan', function ($q) {
                    $q->whereNull('status');
                }))
                ->when($this->status == 'Terverifikasi', fn($q) => $q->whereHas('verifikasiPengadaan', function ($q) {
                    $q->whereNotNull('status');
                }))
                ->where(fn($q) => $q
                    ->where('deskripsi', 'like', '%' . $this->cari . '%'))
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }
}
