<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Stokmasuk;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\StokMasuk;
use App\Models\PengadaanPemesanan;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $bulan;

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        StokMasuk::findOrFail($id)->forceDelete();
        session()->flash('success', 'Berhasil menghapus data');
    }

    public function render()
    {
        return view('livewire.manajemenstok.pengadaanbrgdagang.stokmasuk.index', [
            'pending' => PengadaanPemesanan::select(DB::raw('pengadaan_pemesanan.id id'), 'tanggal', 'supplier_id', 'uraian')->with('supplier')
                ->leftJoin('pengadaan_pemesanan_detail', 'pengadaan_pemesanan.id', '=', 'pengadaan_pemesanan_detail.pengadaan_pemesanan_id')
                ->groupBy('pengadaan_pemesanan.id', 'tanggal', 'supplier_id', 'uraian')
                ->havingRaw('SUM(pengadaan_pemesanan_detail.qty) > (SELECT ifnull(SUM(stok_masuk.qty), 0) FROM stok_masuk WHERE pengadaan_pemesanan_id = pengadaan_pemesanan.id )')
                ->get()->count(),
            'data' => StokMasuk::with(['pengguna.kepegawaianPegawai', 'barangSatuan.barang', 'pengadaanPemesanan.supplier', 'keluar'])
                ->where('created_at', 'like', $this->bulan . '%')
                ->whereNotNull('pengadaan_pemesanan_id')
                ->whereHas('pengadaanPemesanan', fn($q) => $q->where('jenis', 'Barang Dagang'))
                ->where(
                    fn($q) => $q
                        ->whereHas('barangSatuan.barang', fn($q) => $q->where('nama', 'like', '%' . $this->cari . '%'))
                        ->orWhereHas('pengadaanPemesanan', fn($q) => $q->where('uraian', 'like', '%' . $this->cari . '%'))
                )
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }
}
