<?php

namespace App\Livewire\Laporan\Stokbarang;

use App\Models\Goods;
use Livewire\Component;

class Index extends Component
{
    public $year, $month, $search;

    public function print()
    {
        $cetak = view('livewire.laporan.stokbarang.cetak', [
            'cetak' => true,
            'month' => $this->month,
            'year' => $this->year,
            'data' => $this->getData(),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function getData()
    {
        return Goods::where('nama', 'like', '%' . $this->search . '%')->with('user')
            ->with(['goodsBalance' => fn($q) => $q->where('period', 'like',  $this->year . '-' . $this->month . '%')])
            ->with(['incomingStock' => fn($q) => $q->where('date', 'like',  $this->year . '-' . $this->month . '%')])
            ->with(['saleDetail' => fn($q) => $q->whereHas('sale', fn($r) => $r->where('date', 'like',  $this->year . '-' . $this->month . '%'))])
            ->get();
    }

    public function mount()
    {
        $this->year = $this->year ?: date('Y');
        $this->month = $this->month ?: date('m');
    }

    public function render()
    {
        return view('livewire.laporan.stokbarang.index', [
            'data' => $this->getData(),
        ]);
    }
}
