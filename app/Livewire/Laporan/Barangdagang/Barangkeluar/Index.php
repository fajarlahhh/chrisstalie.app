<?php

namespace App\Livewire\Laporan\Barangdagang\Barangkeluar;

use App\Models\Stok;
use App\Models\Barang;
use Livewire\Component;
use App\Models\StokKeluar;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $bulan, $kategori, $jenis;

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
        $this->jenis = $this->jenis ?: 'pertanggalkeluar';
    }

    public function print()
    {
        $this->getData();
        $cetak = view('livewire.laporan.barangdagang.barangkeluar.cetak', [
            'cetak' => true,
            'bulan' => $this->bulan,
            'kategori' => $this->kategori,
            'data' => $this->getData(),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function getData()
    {
        switch ($this->jenis) {
            case 'pertanggalkeluar':
                return Stok::with(['barang.barangSatuanTerkecil', 'stokKeluar'])
                    ->whereNotNull('stok_keluar_id')
                    ->where('tanggal_keluar', 'like', $this->bulan . '%')
                    ->get()
                    ->map(function ($q) {
                        return [
                            'tanggal' => $q->tanggal_keluar,
                            'tanggal_keluar' => $q->tanggal_keluar . '-' . $q->barang_id,
                            'barang' => $q->barang->nama,
                            'satuan' => $q->barang->barangSatuanTerkecil->nama,
                            'harga_jual' => $q->stokKeluar?->harga ?? 0,
                            'qty' => 1,
                        ];
                    })->groupBy('tanggal_keluar')->sortBy('tanggal_keluar')->toArray();
                break;
            case 'pertanggalkedaluarsa':
                return Stok::with(['barang.barangSatuanTerkecil', 'stokKeluar'])
                    ->whereNotNull('stok_keluar_id')
                    ->where('tanggal_keluar', 'like', $this->bulan . '%')
                    ->get()
                    ->map(function ($q) {
                        return [
                            'tanggal_kedaluarsa' => $q->tanggal_kedaluarsa,
                            'barang' => $q->barang->nama,
                            'satuan' => $q->barang->barangSatuanTerkecil->nama,
                            'harga_jual' => $q->stokKeluar?->harga ?? 0,
                            'qty' => 1,
                        ];
                    })->groupBy('tanggal_kedaluarsa')->sortBy('tanggal_kedaluarsa')->toArray();
                break;
            case 'perbarang':
                return Stok::with(['barang', 'stokKeluar'])
                    ->whereNotNull('stok_keluar_id')
                    ->where('tanggal_keluar', 'like', $this->bulan . '%')
                    ->get()
                    ->map(function ($q) {
                        return [
                            'barang' => $q->barang->nama,
                            'barang_id' => $q->barang->nama . $q->barang_id,
                            'satuan' => $q->barang->barangSatuanTerkecil->nama,
                            'harga_jual' => $q->stokKeluar?->harga ?? 0,
                            'qty' => 1,
                        ];
                    })->groupBy('barang_id')->sortBy('barang_id')->toArray();
                break;
        }
    }

    public function render()
    {
        return view('livewire.laporan.barangdagang.barangkeluar.index', [
            'data' => ($this->getData()),
        ]);
    }
}
