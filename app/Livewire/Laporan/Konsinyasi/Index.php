<?php

namespace App\Livewire\Laporan\Konsinyasi;

use App\Exports\PembagianpenjualanExport;
use App\Models\Sale;
use App\Models\Goods;
use App\Models\SaleDetail;
use App\Models\Stock;
use App\Models\Supplier;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $date1, $date2;

    public function mount()
    {
        $this->date1 = $this->date1 ?: date('Y-m-01');
        $this->date2 = $this->date2 ?: date('Y-m-d');
    }

    public function export()
    {
        return (new PembagianpenjualanExport($this->date1, $this->date2))->download('pembagianpenjualan' . $this->date1 . '-' . $this->date2 . '.xls');
    }

    public function getConsignment()
    {
        return SaleDetail::whereHas('sale', fn($q) => $q->whereBetween('date', [$this->date1, $this->date2]))->whereNotNull('consignment_id')->with('consignment')->get()->map(function ($q) {
            return [
                'id' => $q->consignment_id,
                'nama' => $q->consignment->nama
            ];
        })->unique()->toArray();
    }

    public function getPractitioner()
    {
        return (SaleDetail::whereHas('sale', fn($q) => $q->whereBetween('date', [$this->date1, $this->date2]))->whereNotNull('practitioner_id')->with('practitioner')->get()->map(function ($q) {
            return [
                'id' => $q->practitioner_id,
                'nama' => $q->practitioner->nama ?: $q->practitioner->employee->nama
            ];
        })->unique()->toArray());
    }

    public function getData()
    {
        return (SaleDetail::whereNotNull('consignment_id')->whereHas('sale', fn($q) => $q->whereBetween('date', [$this->date1, $this->date2]))->with(['sale', 'goods'])->get()->map(function ($q) {
            return [
                'id' => $q->goods_id,
                'nama' => $q->goods->nama,
                'unit' => $q->goods->unit,
                'price' => $q->price,
                'qty' => $q->qty,
                'discount' => $q->price * $q->discount / 100,
                'price_discount' => $q->price - ($q->price * $q->discount / 100),
                'practitioner_id' => $q->practitioner_id,
                'consignment_id' => $q->consignment_id,
                'capital' => $q->capital,
                'office_portion' => $q->office_portion,
                'practitioner_portion' => $q->practitioner_portion,
            ];
        })->sortBy('nama')->toArray());
    }

    public function render()
    {
        return view('livewire.laporan.konsinyasi.index', [
            'data' => $this->getData(),
            'consignment' => $this->getConsignment(),
            'practitioner' => $this->getPractitioner(),
        ]);
    }
}
