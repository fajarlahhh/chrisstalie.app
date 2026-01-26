<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Pemesanan;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\PengadaanPemesanan;
use Illuminate\Support\Facades\DB;
use App\Models\PengadaanPermintaan;
use App\Models\PengadaanVerifikasi;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $status = 'Belum Proses', $bulan;

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
            PengadaanVerifikasi::where('pengadaan_permintaan_id', $id)->forceDelete();
            session()->flash('success', 'Berhasil menghapus data');
        } catch (\Throwable $th) {
            session()->flash('danger', 'Gagal menghapus data');
        };
    }
    private function getData()
    {
        if ($this->status == 'Belum Proses') {
            $data = PengadaanPermintaan::with([
                'pengguna.kepegawaianPegawai',
                'pengadaanPermintaanDetail.barangSatuan.satuanKonversi',
                'pengadaanPermintaanDetail.barangSatuan.barang',
                'pengadaanPemesanan.stokMasuk',
            ])
                ->whereHas('pengadaanPermintaanDetail', function ($q) {
                    $q->whereColumn(DB::raw('ifnull(qty_sudah_dipesan, 0)'), '<', 'qty_disetujui');
                })
                ->where(fn($q) => $q
                    ->where('deskripsi', 'like', '%' . $this->cari . '%'))
                ->when(auth()->user()->hasRole('operator|guest'), fn($q) => $q->whereIn('jenis_barang', ['Persediaan Apotek', 'Alat Dan Bahan']))
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return $data;
        } else {
            $data = PengadaanPemesanan::with([
                'supplier',
                'pengguna.kepegawaianPegawai',
                'pengadaanPemesananDetail.barangSatuan.barang',
                'pengadaanPemesananDetail.barangSatuan.satuanKonversi',
                'pengadaanPermintaan',
                'pengadaanVerifikasi.pengguna',
            ])
                ->whereHas('pengadaanPermintaan', function ($q) {
                    $q->where(fn($q) => $q
                        ->where('deskripsi', 'like', '%' . $this->cari . '%'));
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return $data;
        }
    }

    public function render()
    {
        return view('livewire.manajemenstok.pengadaanbrgdagang.pemesanan.index', [
            'data' => $this->getData()
        ]);
    }
}
