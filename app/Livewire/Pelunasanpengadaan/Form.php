<?php

namespace App\Livewire\Pelunasanpengadaan;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\Expenditure;
use App\Models\ExpenditureDetail;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $purchaseData = [], $detail = [], $otherCost = [], $purchase;
    public $date, $description, $cost, $receipt, $month, $year, $purchase_id;

    public function mount(Expenditure $data)
    {
        $this->previous = url()->previous();
        $this->month = $this->month ?: date('m');
        $this->year = $this->year ?: date('Y');
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->purchaseData = Purchase::select('purchases.id', 'receipt', 'date', 'description', 'supplier_id', 'discount', 'ppn')
            ->groupBy('purchases.id', 'receipt', 'date', 'description', 'supplier_id', 'discount', 'ppn')
            ->leftJoin('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->havingRaw('SUM(purchase_details.qty) > (SELECT ifnull(SUM(incoming_stocks.qty), 0) FROM incoming_stocks WHERE incoming_stocks.purchase_id = purchases.id )')
            ->with('incomingStock')->with('supplier')->with('purchaseDetail.goods')->get()->map(fn($q) => [
                'id' => $q->id,
                'date' => $q->date,
                'description' => $q->description,
                'due_date' => $q->due_date,
                'receipt' => $q->receipt,
                'supplier_id' => $q->supplier_id,
                'discount' => $q->discount,
                'ppn' => $q->ppn,
                'supplier' => $q->supplier_id ? $q->supplier->nama . ($q->supplier->consignment == 1 ? ' (Konsinyasi)' : '') : null,
                'consignment' => $q->supplier_id ? $q->supplier->consignment : null,
                'purchase_detail' => $q->purchaseDetail->map(fn($r) => [
                    'goods' => $r->goods->nama,
                    'unit' => $r->goods->unit,
                    'price' => $r->price,
                    'expired_date' => null,
                    'batch_number' => null,
                    'goods_name_qty' => $r->goods->nama . ' (' . $r->qty . ')',
                    'goods_id' => $r->goods_id,
                    'remaining' => $r->qty - $q->incomingStock?->where('goods_id', $r->goods_id)->sum('qty'),
                    'stock_in' => 0,
                    'qty' => $r->qty,
                ])->toArray()
            ])->toArray();
        if ($this->data->exists) {
            $this->detail = $this->data->expenditureDetail->map(fn($q) => [
                'description' => $q['description'],
                'cost' =>  $q['cost']
            ]);
            $this->purchase = collect($this->purchaseData)->where('id', $this->purchase_id)->first();
        }
    }

    public function updatedPurchaseId()
    {
        $this->purchase = collect($this->purchaseData)->where('id', $this->purchase_id)->first();
        $this->detail = collect($this->purchase['purchase_detail'])->map(fn($q) => [
            'description' => $q['goods']. " (" . $q['qty'] . ")",
            'cost' => $q['qty'] * $q['price']
        ])->toArray();
        $this->detail[] = [
            'description' => 'Discount',
            'cost' => $this->purchase['discount']
        ];
        $this->detail[] = [
            'description' => 'PPN',
            'cost' => $this->purchase['ppn']
        ];
    }

    public function submit()
    {
        $this->validate([
            'date' => 'required',
            'purchase_id' => 'required',
        ]);

        DB::transaction(function () {
            $this->data->type = 'Pelunasan Pengadaan';
            $this->data->date = $this->date;
            $this->data->cost = collect($this->detail)->where('description', '!=', 'Discount')->sum('cost') - collect($this->detail)->where('description', 'Discount')->sum('cost');
            $this->data->purchase_id = $this->purchase_id;
            $this->data->description = "Pelunasan Pengadaan " . collect($this->detail)->pluck('description')->join(',');
            $this->data->user_id = auth()->id();
            $this->data->save();

            ExpenditureDetail::where('expenditure_id', $this->data->id)->delete();
            ExpenditureDetail::insert(collect($this->detail)->map(fn($q, $index) => [
                'description' => $q['description'],
                'cost' => $q['cost'],
                'expenditure_id' => $this->data->id
            ])->toArray());

            session()->flash('success', 'Berhasil menyimpan data');
        });

        $this->redirect($this->previous);
        return redirect()->to($this->previous);
    }

    public function render()
    {
        return view('livewire.pelunasanpengadaan.form');
    }
}
