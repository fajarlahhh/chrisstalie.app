<?php

namespace App\Livewire\Laporan\Penjualan;

use App\Models\Barang;
use Livewire\Component;
use App\Models\Penjualan;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $tanggal1, $tanggal2;

    public function mount()
    {
        $this->tanggal1 = $this->tanggal1 ?: date('Y-m-d');
        $this->tanggal2 = $this->tanggal2 ?: date('Y-m-d');
    }

    public function print()
    {
        $this->getData();
        $cetak = view('livewire.laporan.penjualan.cetak', [
            'cetak' => true,
            'tanggal1' => $this->tanggal1,
            'tanggal2' => $this->tanggal2,
            'data' => $this->getData(),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function getData()
    {
        return Penjualan::with(['barang', 'barangSatuan'])
        ->whereIn('barang_id', Barang::where('persediaan', 'Apotek')->pluck('id'))
            ->whereBetween('tanggal', [$this->tanggal1, $this->tanggal2])
            ->orderBy('tanggal')
            ->get();
    }

    public function render()
    {
        return view('livewire.laporan.penjualan.index', [
            'data' => ($this->getData()),
        ]);
    }
}
