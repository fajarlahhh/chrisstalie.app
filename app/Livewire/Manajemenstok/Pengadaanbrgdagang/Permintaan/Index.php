<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Permintaan;

use Livewire\Component;
use App\Models\PengadaanPermintaan;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $status = 'Pending', $tanggal;

    public function updated()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->tanggal = $this->tanggal ?: date('Y-m-d');
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

    public function render()
    {
        return view('livewire.manajemenstok.pengadaanbrgdagang.permintaan.index', [
            'data' => PengadaanPermintaan::with([
                'pengguna.kepegawaianPegawai',
                'pengadaanPermintaanDetail.barangSatuan.satuanKonversi',
                'pengadaanPermintaanDetail.barangSatuan.barang',
                'pengadaanPemesanan.stokMasuk',
                'PengadaanVerifikasiPending',
                'PengadaanVerifikasiDisetujui',
                'PengadaanVerifikasiDitolak',
                'pengadaanVerifikasi.pengguna'
            ])
                ->where(fn($q) => $q
                    ->where('deskripsi', 'like', '%' . $this->cari . '%'))
                ->when($this->status == 'Pending', fn($q) => $q->whereHas('pengadaanVerifikasi', function ($q) {
                    $q->whereNull('status');
                })->orWhereDoesntHave('pengadaanVerifikasi'))
                ->when($this->status == 'Ditolak', fn($q) => $q->whereHas('PengadaanVerifikasiDitolak'))
                ->when($this->status == 'Disetujui', fn($q) => $q->whereHas('PengadaanVerifikasiDisetujui')->where('created_at', 'like', $this->tanggal . '%'))
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }
}
