<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Pembelian;

use Livewire\Component;
use App\Models\PengadaanPemesanan;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\PengadaanPermintaan;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $bulan, $status = 1;

    public function updated()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
    }

    public function delete($id)
    {
        PengadaanPemesanan::findOrFail($id)->forceDelete();
        session()->flash('success', 'Berhasil menghapus data');
    }

    public function render()
    {
        return view('livewire.manajemenstok.pengadaanbrgdagang.pembelian.index', [
            'data' => $this->status == 1 ? PengadaanPermintaan::with([
                'pengguna.kepegawaianPegawai',
                'pengadaanPermintaanDetail.barangSatuan.satuanKonversi',
                'pengadaanPermintaanDetail.barangSatuan.barang',
                'pengadaanVerifikasi.pengguna.kepegawaianPegawai' => fn($q) => $q->whereNotNull('status')
            ])
                ->when($this->status == 'Pending', fn($q) => $q->whereHas('pengadaanVerifikasi', function ($q) {
                    $q->whereNull('status');
                }))
                ->whereHas('pengadaanVerifikasi', function ($q) {
                    $q->whereNotNull('status');
                })
                ->whereDoesntHave('pengadaanPemesanan')
                ->where(fn($q) => $q
                    ->where('deskripsi', 'like', '%' . $this->cari . '%'))
                ->orderBy('created_at', 'desc')
                ->paginate(10) :
                PengadaanPemesanan::with([
                    'pengadaanPemesananDetail.barangSatuan.barang',
                    'pengadaanPemesananDetail.barangSatuan.satuanKonversi',
                    'pengguna.kepegawaianPegawai',
                    'stokMasuk',
                    'pengadaanPelunasanPemesanan',
                    'supplier',
                    'pengadaanPermintaan'
                ])
                ->where('jenis', 'Barang Dagang')
                ->where('tanggal', 'like', $this->bulan . '%')
                ->where(fn($q) => $q->where('uraian', 'like', '%' . $this->cari . '%'))
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }
}
