<?php

namespace App\Livewire\Manajemenstok\Barangmasuk;

use App\Models\Stock;
use Livewire\Component;
use App\Models\Purchase;
use Illuminate\Support\Str;
use App\Models\IncomingStock;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $previous, $purchaseData = [], $purchase;
    public $date, $description, $due_date, $receipt, $goods = [], $purchase_id;

    public function mount()
    {
        $this->previous = url()->previous();
        $this->date = $this->date ?: date('Y-m-d');
        $this->purchaseData = Purchase::select('purchases.id', 'receipt', 'date', 'description', 'supplier_id')
            ->groupBy('purchases.id', 'receipt', 'date', 'description', 'supplier_id')
            ->leftJoin('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->havingRaw('SUM(purchase_details.qty) > (SELECT ifnull(SUM(incoming_stocks.qty), 0) FROM incoming_stocks WHERE incoming_stocks.purchase_id = purchases.id )')
            ->with('incomingStock')->with('supplier')->with('purchaseDetail.goods')->get()->map(fn($q) => [
                'id' => $q->id,
                'date' => $q->date,
                'description' => $q->description,
                'due_date' => $q->due_date,
                'receipt' => $q->receipt,
                'supplier_id' => $q->supplier_id,
                'supplier' => $q->supplier_id ? $q->supplier->name . ($q->supplier->consignment == 1 ? ' (Konsinyasi)' : '') : null,
                'consignment' => $q->supplier_id ? $q->supplier->consignment : null,
                'purchase_detail' => $q->purchaseDetail->map(fn($r) => [
                    'goods' => $r->goods->name,
                    'unit' => $r->goods->unit,
                    'price' => $r->price,
                    'expired_date' => null,
                    'batch_number' => null,
                    'goods_name_qty' => $r->goods->name . ' (' . $r->qty . ')',
                    'goods_id' => $r->goods_id,
                    'remaining' => $r->qty - $q->incomingStock?->where('goods_id', $r->goods_id)->sum('qty'),
                    'stock_in' => 0,
                    'qty' => $r->qty,
                ])->toArray()
            ])->toArray();
    }

    public function updatedPurchaseId($value)
    {
        $this->purchase = collect($this->purchaseData)->where('id', $value)->first();

        $this->goods = collect($this->purchase['purchase_detail']);
    }

    public function submit()
    {
        $this->validate([
            'purchase_id' => 'required',
            'date' => 'required',
            'goods' => 'required|array',
            'goods.*.batch_number' => 'required',
            'goods.*.goods_id' => 'required|integer',
            'goods.*.qty' => 'required|integer',
            'goods.*.stock_in' => ['required', 'numeric', function ($attribute, $value, $fail) {
                $data = explode('.', $attribute);
                if ($value > $this->goods[$data[1]]['remaining']) {
                    $fail("The $attribute field must not be greater than " . $this->goods[$data[1]]['remaining']);
                }
            }],
        ]);

        DB::transaction(function () {
            $stock = [];
            foreach (collect($this->goods)->where('stock_in', '>', 0) as $row) {
                $data = new IncomingStock();
                $data->date = $this->date;
                $data->description = $this->description;
                $data->qty = $row['stock_in'];
                $data->expired_date = $row['expired_date'];
                $data->batch_number = $row['batch_number'];
                $data->consignment = $this->purchase['consignment'];
                $data->purchase_id = $this->purchase['id'];
                $data->goods_id = $row['goods_id'];
                $data->user_id = auth()->id();
                $data->save();

                // for ($i = 0; $i < $row['qty']; $i++) {
                //     $stock[] = [
                //         'id' => Str::uuid(),
                //         'date_in_stock' => $this->date,
                //         'purchase_price' => $row['price'],
                //         'consignment' => $this->purchase['consignment'],
                //         'expired_date' => $row['expired_date'],
                //         'batch_number' => $row['batch_number'],
                //         'goods_id' => $row['goods_id'],
                //         'incoming_stock_id' => $data->id,
                //         'supplier_id' => $this->purchase['supplier_id'],
                //         'created_at' => now(),
                //         'updated_at' => now(),
                //     ];
                // }
            }
            // Stock::insert($stock);
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function render()
    {
        return view('livewire.manajemenstok.barangmasuk.form');
    }
}
