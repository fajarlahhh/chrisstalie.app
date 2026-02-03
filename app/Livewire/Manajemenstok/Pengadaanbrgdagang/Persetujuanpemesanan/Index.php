<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Persetujuanpemesanan;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\PengadaanPermintaan;
use App\Models\PengadaanPemesanan;


class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $status = 'Belum Disetujui', $bulan;

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
        try {
            PengadaanPermintaan::findOrFail($id)
                ->forceDelete();
            session()->flash('success', 'Berhasil menghapus data');
        } catch (\Throwable $th) {
            session()->flash('danger', 'Gagal menghapus data');
        };
    }
    private function getData()
    {
        $data = PengadaanPemesanan::with([
            'supplier',
            'pengguna.kepegawaianPegawai',
            'pengadaanPemesananDetail.barangSatuan.barang',
            'pengadaanPemesananDetail.barangSatuan.satuanKonversi',
            'pengadaanPermintaan',
            'pengadaanPemesananVerifikasi.pengguna',
        ])
            ->when($this->status == 'Belum Disetujui', fn($q) => $q->whereHas('pengadaanPemesananVerifikasi', function ($q) {
                $q->whereNull('status');
            }))
            ->when(
                $this->status == 'Sudah Disetujui',
                fn($q) => $q->whereHas('pengadaanPemesananVerifikasi', function ($q) {
                    $q->where('status', 'Disetujui')->where('waktu_verifikasi', 'like', $this->bulan . '%');
                })
            )
            ->where(fn($q) => $q
                ->where('uraian', 'like', '%' . $this->cari . '%')
                ->orWhereHas('supplier', function ($q) {
                    $q->where('nama', 'like', '%' . $this->cari . '%');
                })
                ->orWhereHas('pengadaanPermintaan', function ($q) {
                    $q->where(fn($r) => $r->where('deskripsi', 'like', '%' . $this->cari . '%')
                        ->orWhere('jenis_barang', 'like', '%' . $this->cari . '%'))
                        // ->when(auth()->user()->hasRole('operator|guest'), fn($r) => $r->whereIn('jenis_barang', ['Persediaan Apotek', 'Alat Dan Bahan']))
                    ;
                })
                ->orWhere('catatan', 'like', '%' . $this->cari . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return $data;
    }

    public function render()
    {
        return view('livewire.manajemenstok.pengadaanbrgdagang.persetujuanpemesanan.index', [
            'data' => $this->getData()
        ]);
    }
}
