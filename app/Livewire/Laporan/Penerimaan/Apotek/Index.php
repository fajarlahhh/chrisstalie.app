<?php

namespace App\Livewire\Laporan\Penerimaan\Apotek;

use App\Models\Sale;
use Livewire\Component;
use App\Models\SaleDetail;

class Index extends Component
{
    public $date1, $date2, $search;

    public function mount()
    {
        $this->date1 = $this->date1 ?: date('Y-m-01');
        $this->date2 = $this->date2 ?: date('Y-m-d');
        $this->search = $this->search ?: '';
    }

    public function getData()
    {
        return collect(SaleDetail::with(['sale', 'goods.consignment'])->whereHas('goods', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))->whereHas('sale', fn($q) => $q->where('date', '>=', $this->date1)->where('date', '<=', $this->date2))->get()->groupBy('goods_id')->map(fn($q) => [
            'id' => $q->first()->goods->nama . '.' . $q->first()->price - $q->first()->discount,
            'nama' => $q->first()->goods->nama,
            'unit' => $q->first()->goods->unit,
            'consignment' => $q->first()->goods->consignment?->nama,
            'price' => $q->first()->price,
            'price_discount' => $q->first()->price - $q->first()->discount,
            'qty' => $q->sum('qty'),
        ])->groupBy('id')->values())->map(fn($q) => [
            'nama' => $q[0]['nama'],
            'unit' => $q[0]['unit'],
            'consignment' => $q[0]['consignment'],
            'price' => $q[0]['price'],
            'price_discount' => $q[0]['price_discount'],
            'qty' => $q[0]['qty'],
            'total' => collect($q)->sum(fn($r) => $r['qty'] * $r['price_discount']),
        ])->sortBy('nama')->toArray();
    }

    public function print()
    {
        $cetak = view('livewire.laporan.penerimaan.apotek.cetak', [
            'cetak' => true,
            'date1' => $this->date1,
            'date2' => $this->date2,
            'data' => $this->getData(),
            'admin'=> (Sale::where('date', '>=', $this->date1)->where('date', '<=', $this->date2)->get()),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function render()
    {
        return view('livewire.laporan.penerimaan.apotek.index', [
            'data' => $this->getData(),
            'admin'=> (Sale::where('date', '>=', $this->date1)->where('date', '<=', $this->date2)->get()),
        ]);
    }
}
