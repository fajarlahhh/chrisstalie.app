<?php

namespace App\Livewire\Laporan\Lhk;

use Livewire\Component;
use App\Models\Expenditure;
use App\Models\Kasir;
use App\Models\Sale;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $date, $date2, $office;

    public function mount()
    {
        $this->date = $this->date ?: date('Y-m-d');
    }

    public function getData()
    {
        return Expenditure::with(['pegawai', 'pengguna'])->where('type', 'form')->when($this->office, fn($q) => $q->where('office', $this->office))->where('date', $this->date)->get();
    }


    public function getPenerimaanPelayananTindakan()
    {
        return Kasir::where('date', $this->date)->get();
    }


    public function getPenerimaanPenjualan()
    {
        return Sale::where('date', $this->date)->get();
    }

    public function print()
    {
        $cetak = view('livewire.laporan.lhk.cetak', [
            'cetak' => true,
            'date' => $this->date,
            'data' => [
                'Pengeluaran' => $this->getData(),
                'Penerimaan Klinik' => $this->getPenerimaanPelayananTindakan(),
                'Penerimaan Apotek' => $this->getPenerimaanPenjualan(),
            ]
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function render()
    {
        return view('livewire.laporan.lhk.index', [
            'data' => [
                'Pengeluaran' => $this->getData(),
                'Penerimaan Klinik' => $this->getPenerimaanPelayananTindakan(),
                'Penerimaan Apotek' => $this->getPenerimaanPenjualan(),
            ]
        ]);
    }
}
