<?php

namespace App\Livewire\Laporan\Konsinyasi;

use App\Exports\PembagianpenjualanExport;
use App\Models\Sale;
use App\Models\Barang;
use App\Models\SaleDetail;
use App\Models\Stok;
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
        return SaleDetail::whereHas('sale', fn($q) => $q->whereBetween('date', [$this->date1, $this->date2]))->whereNotNull('consignment_id')->with('konsinyasi')->get()->map(function ($q) {
            return [
                'id' => $q->consignment_id,
                'nama' => $q->konsinyasi->nama
            ];
        })->unique()->toArray();
    }

    public function getNakes()
    {
        return (SaleDetail::whereHas('sale', fn($q) => $q->whereBetween('date', [$this->date1, $this->date2]))->whereNotNull('nakes_id')->with('nakes')->get()->map(function ($q) {
            return [
                'id' => $q->nakes_id,
                'nama' => $q->nakes->nama ?: $q->nakes->pegawai->nama
            ];
        })->unique()->toArray());
    }

    public function getData()
    {
        return (SaleDetail::whereNotNull('consignment_id')->whereHas('sale', fn($q) => $q->whereBetween('date', [$this->date1, $this->date2]))->with(['sale', 'goods'])->get()->map(function ($q) {
            return [
                'id' => $q->goods_id,
                'nama' => $q->goods->nama,
                'satuan' => $q->goods->satuan,
                'harga' => $q->harga,
                'qty' => $q->qty,
                'discount' => $q->harga * $q->discount / 100,
                'harga_discount' => $q->harga - ($q->harga * $q->discount / 100),
                'nakes_id' => $q->nakes_id,
                'consignment_id' => $q->consignment_id,
                'modal' => $q->modal,
                'porsi_kantor' => $q->porsi_kantor,
                'porsi_nakes' => $q->porsi_nakes,
            ];
        })->sortBy('nama')->toArray());
    }

    public function render()
    {
        return view('livewire.laporan.konsinyasi.index', [
            'data' => $this->getData(),
            'konsinyasi' => $this->getConsignment(),
            'nakes' => $this->getNakes(),
        ]);
    }
}
