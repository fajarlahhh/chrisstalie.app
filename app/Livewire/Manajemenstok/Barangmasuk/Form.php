<?php

namespace App\Livewire\Manajemenstok\Barangmasuk;

use App\Models\Stok;
use Livewire\Component;
use App\Models\Purchase;
use Illuminate\Support\Str;
use App\Models\IncomingStok;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $previous, $purchaseData = [], $purchase;
    public $date, $uraian, $due_date, $receipt, $goods = [], $purchase_id;

    public function mount()
    {
        $this->previous = url()->previous();
        $this->date = $this->date ?: date('Y-m-d');
        $this->purchaseData = Purchase::select('purchases.id', 'receipt', 'date', 'uraian', 'supplier_id')
            ->groupBy('purchases.id', 'receipt', 'date', 'uraian', 'supplier_id')
            ->leftJoin('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->havingRaw('SUM(purchase_details.qty) > (SELECT ifnull(SUM(incoming_stoks.qty), 0) FROM incoming_stoks WHERE incoming_stoks.purchase_id = purchases.id )')
            ->with('stokMasuk')->with('supplier')->with('purchaseDetail.goods')->get()->map(fn($q) => [
                'id' => $q->id,
                'date' => $q->date,
                'uraian' => $q->uraian,
                'due_date' => $q->due_date,
                'receipt' => $q->receipt,
                'supplier_id' => $q->supplier_id,
                'supplier' => $q->supplier_id ? $q->supplier->nama . ($q->supplier->konsinyasi == 1 ? ' (Konsinyasi)' : '') : null,
                'konsinyasi' => $q->supplier_id ? $q->supplier->konsinyasi : null,
                'purchase_detail' => $q->purchaseDetail->map(fn($r) => [
                    'goods' => $r->goods->nama,
                    'satuan' => $r->goods->satuan,
                    'harga' => $r->harga,
                    'expired_date' => null,
                    'batch_number' => null,
                    'goods_name_qty' => $r->goods->nama . ' (' . $r->qty . ')',
                    'goods_id' => $r->goods_id,
                    'remaining' => $r->qty - $q->stokMasuk?->where('goods_id', $r->goods_id)->sum('qty'),
                    'stok_in' => 0,
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
            'goods.*.stok_in' => ['required', 'numeric', function ($attribute, $value, $fail) {
                $data = explode('.', $attribute);
                if ($value > $this->goods[$data[1]]['remaining']) {
                    $fail("The $attribute field must not be greater than " . $this->goods[$data[1]]['remaining']);
                }
            }],
        ]);

        DB::transaction(function () {
            $stok = [];
            foreach (collect($this->goods)->where('stok_in', '>', 0) as $row) {
                $data = new IncomingStok();
                $data->date = $this->date;
                $data->uraian = $this->uraian;
                $data->qty = $row['stok_in'];
                $data->expired_date = $row['expired_date'];
                $data->batch_number = $row['batch_number'];
                $data->konsinyasi = $this->purchase['konsinyasi'];
                $data->purchase_id = $this->purchase['id'];
                $data->goods_id = $row['goods_id'];
                $data->user_id = auth()->id();
                $data->save();

                // for ($i = 0; $i < $row['qty']; $i++) {
                //     $stok[] = [
                //         'id' => Str::uuid(),
                //         'date_in_stok' => $this->date,
                //         'purchase_harga' => $row['harga'],
                //         'konsinyasi' => $this->purchase['konsinyasi'],
                //         'expired_date' => $row['expired_date'],
                //         'batch_number' => $row['batch_number'],
                //         'goods_id' => $row['goods_id'],
                //         'incoming_stok_id' => $data->id,
                //         'supplier_id' => $this->purchase['supplier_id'],
                //         'created_at' => now(),
                //         'updated_at' => now(),
                //     ];
                // }
            }
            // Stok::insert($stok);
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function render()
    {
        return view('livewire.manajemenstok.barangmasuk.form');
    }
}
