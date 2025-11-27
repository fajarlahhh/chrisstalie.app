<?php

namespace App\Livewire\Laporan\Barangdagang\Barangmasuk;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\StokMasuk;

class Index extends Component
{
    #[Url]
    public $bulan, $kategori;

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
    }

    public function print()
    {
        $this->getData();
        $cetak = view('livewire.laporan.barangdagang.barangmasuk.cetak', [
            'cetak' => true,
            'bulan' => $this->bulan,
            'kategori' => $this->kategori,
            'data' => $this->getData(),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function getData()
    {
        return StokMasuk::with(['barang', 'pembelian.pembelianDetail', 'barangSatuan', 'pembelian.supplier'])
            ->where('tanggal', 'like', $this->bulan . '%')
            ->when($this->kategori, fn($q) => $q->whereHas('pembelian', fn($r) => $r->where('jenis', $this->kategori)))
            ->orderBy('tanggal', 'desc')
            ->get()->map(function ($q) {
                return [
                    'tanggal' => $q->tanggal,
                    'barang' => $q->barang->nama,
                    'satuan' => $q->barangSatuan->nama,
                    'no_batch' => $q->no_batch,
                    'tanggal_kedaluarsa' => $q->tanggal_kedaluarsa,
                    'harga_beli' => $q->pembelian->pembelianDetail->where('barang_id', $q->barang_id)->first()->harga_beli,
                    'qty' => $q->qty,
                    'total' => $q->qty * $q->pembelian->pembelianDetail->where('barang_id', $q->barang_id)->first()->harga_beli,
                    'supplier' => $q->pembelian->supplier?->nama,
                    'uraian' => $q->pembelian->uraian,
                ];
            })->toArray();
    }

    public function render()
    {
        return view('livewire.laporan.barangdagang.barangmasuk.index', [
            'data' => ($this->getData()),
        ]);
    }
}
