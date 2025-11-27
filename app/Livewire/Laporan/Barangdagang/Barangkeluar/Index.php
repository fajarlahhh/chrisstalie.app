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
    public $bulan, $jenis = "Rekap", $kategori;

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
    }

    public function print()
    {
        $this->getData();
        $cetak = view('livewire.laporan.barangdagang.barangkeluar.cetak', [
            'cetak' => true,
            'bulan' => $this->bulan,
            'jenis' => $this->jenis,
            'kategori' => $this->kategori,
            'data' => $this->getData(),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function getData()
    {
        if ($this->jenis == "Rekap") {
            return StokKeluar::with(['barang.barangSatuanUtama'])
                ->where('tanggal', 'like', $this->bulan . '%')
                ->when($this->kategori, fn($q) => $q->whereIn('barang_id', Barang::where('type', $this->kategori)->pluck('id')))
                ->orderBy('tanggal', 'desc')
                ->get()->map(function ($q) {
                    return [
                        'tanggal' => $q->tanggal,
                        'barang' => $q->barang->nama,
                        'satuan' => $q->barang->barangSatuanUtama->nama,
                        'harga_jual' => $q->harga,
                        'qty' => $q->qty / $q->barang->barangSatuanUtama->rasio_dari_terkecil,
                        'total' => $q->qty * $q->harga,
                    ];
                })->groupBy('tanggal')->sortBy('tanggal')->toArray();
        } else {
            return Stok::with(['barang.barangSatuanUtama', 'stokKeluar'])->whereNotNull('stok_keluar_id')
                ->when($this->kategori, fn($q) => $q->whereIn('barang_id', Barang::where('type', $this->kategori)->pluck('id')))
                ->where('tanggal_keluar', 'like', $this->bulan . '%')
                ->get()->map(function ($q) {
                    return [
                        'tanggal' => $q->tanggal_keluar,
                        'tanggal_kedaluarsa' => $q->tanggal_kedaluarsa,
                        'barang' => $q->barang->nama,
                        'satuan' => $q->barang->barangSatuanUtama->nama,
                        'harga_jual' => $q->stokKeluar?->harga ?? 0,
                        'qty' => ($q->stokKeluar?->rasio_dari_terkecil ?? 0) != 0 ? 1 / $q->stokKeluar->rasio_dari_terkecil : 0,
                    ];
                })->groupBy('tanggal')->sortBy('tanggal')->toArray();
        }
    }

    public function render()
    {
        return view('livewire.laporan.barangdagang.barangkeluar.index', [
            'data' => ($this->getData()),
        ]);
    }
}
