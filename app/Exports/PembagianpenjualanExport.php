<?php

namespace App\Exports;

use App\Models\SaleDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class PembagianpenjualanExport implements FromView
{
    use Exportable;

    public $date1, $date2;

    public function __construct(string $date1, string $date2)
    {
        $this->date1 = $date1;
        $this->date2 = $date2;
    }

    public function getConsignment()
    {
        return SaleDetail::whereHas('sale', fn($q) => $q->whereBetween('date', [$this->date1, $this->date2]))->whereNotNull('consignment_id')->with('konsinyasi')->get()->map(function ($q) {
            return [
                'id' => $q->consignment_id,
                'name' => $q->konsinyasi->name
            ];
        })->unique()->toArray();
    }

    public function getData()
    {
        return (SaleDetail::whereHas('sale', fn($q) => $q->whereBetween('date', [$this->date1, $this->date2]))->with(['sale', 'goods'])->get()->map(function ($q) {
            $harga = $q->harga * $q->qty;
            $discount = ($q->harga * $q->discount);
            $hargaAfterDiscount = ($q->harga - $discount) * $q->qty;
            return [
                'id' => $q->goods_id,
                'name' => $q->goods->name,
                'satuan' => $q->goods->satuan,
                'harga' => $harga,
                'qty' => $q->qty,
                'hargaAfterDiscount' => $hargaAfterDiscount,
                'discount' => $discount,
                'consignment_id' => $q->consignment_id,
                'consignment_portion' => $q->porsi_kantor > 0 ? $harga - $q->porsi_kantor : 0,
                'porsi_kantor' => $hargaAfterDiscount - $q->porsi_kantor
            ];
        })->sortBy('name')->toArray());
    }

    public function view(): View
    {
        return view('livewire.laporan.konsinyasi.cetak', [
            'cetak' => true,
            'date1' => $this->date1,
            'date2' => $this->date2,
            'data' => $this->getData(),
            'konsinyasi' => $this->getConsignment(),
        ]);
    }
}
