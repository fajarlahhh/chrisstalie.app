<?php

namespace App\Livewire\Penjualan\Resep;

use App\Models\Sale;
use App\Models\Goods;
use App\Models\Stock;
use App\Models\Patient;
use Livewire\Component;
use App\Models\SaleDetail;
use App\Models\Practitioner;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $patient_id, $patient, $goodsData = [],  $date, $description, $powerFee = 2000, $receiptFee = 3000,  $receipt = [], $type = "Cash", $cash = 0,  $total = 0, $payment_description, $practitioner_id, $practitionerData = [];

    public function addGoods($index)
    {
        $this->receipt[$index]['goods'][] =
            [
                'id' => null,
                'nama' => null,
                'unit' => null,
                'price' => 0,
                'qty' => 1,
                'discount' => 0,
                'total' => 0,
                'office_portion' => null,
                'consignment_id' => null
            ];
        $this->total = collect($this->receipt)->pluck('goods')->flatten(1)->sum('total');
    }

    public function deleteGoods($index1, $index2)
    {
        unset($this->receipt[$index1]['goods'][$index2]);
        $this->receipt[$index1]['goods'] = array_merge($this->receipt[$index1]['goods']);
        $this->total = collect($this->receipt)->pluck('goods')->flatten(1)->sum('total');
    }

    public function setPrice($index1, $index2)
    {
        $goods = $this->receipt[$index1]['goods'][$index2];
        if ($goods['id']) {
            $data = collect($this->goodsData)->where('id', $goods['id'])->first();
            $this->receipt[$index1]['goods'][$index2]['price'] = $data['price'] ?? 0;
            $this->receipt[$index1]['goods'][$index2]['nama'] = $data['nama'] ?? null;
            $this->receipt[$index1]['goods'][$index2]['unit'] = $data['unit'] ?? null;
            $this->receipt[$index1]['goods'][$index2]['office_portion'] = $data['office_portion'] ?? null;
            $this->receipt[$index1]['goods'][$index2]['consignment_id'] = $data['consignment_id'] ?? null;
            $this->receipt[$index1]['goods'][$index2]['capital'] = $data['consignment_id'] ? $data['capital'] : 0;
            $this->receipt[$index1]['goods'][$index2]['office_portion'] = $data['consignment_id'] ? $data['office_portion'] : 1;
            $this->receipt[$index1]['goods'][$index2]['practitioner_portion'] = $data['consignment_id'] ? $data['practitioner_portion'] : 0;
        }
        $qty = (float)$this->receipt[$index1]['goods'][$index2]['qty'];
        $discount = (float)$this->receipt[$index1]['goods'][$index2]['discount'];
        $this->receipt[$index1]['goods'][$index2]['total'] = ($this->receipt[$index1]['goods'][$index2]['price'] - ($this->receipt[$index1]['goods'][$index2]['price'] * ($discount ?? 0) / 100)) * ($qty ?? 0);
        $this->total = collect($this->receipt)->pluck('goods')->flatten(1)->sum('total');
    }

    public function submit()
    {
        $this->validate([
            'practitioner_id' => 'required',
            'date' => 'required|date',
            'receipt' => 'required|array',
            'cash' => 'required|numeric|min:' . $this->total,
        ]);

        // $goods = collect($this->receipt)->pluck('goods')->flatten(1)->groupBy('id')->map(fn($q) => [
        //     'id' => $q->first()['id'],
        //     'nama' => $q->first()['nama'],
        //     'unit' => $q->first()['unit'],
        //     'qty' => $q->sum('qty'),
        //     'price' => $q->first()['price'],
        //     'discount' => $q->first()['discount'],
        //     'total' => $q->sum(fn($q) => $q['qty'] * ($q['price'] - ($q['price'] * $q['discount'] / 100))),
        //     'office_portion' => $q->first()['office_portion'],

        // ]);

        // foreach ($goods as $key1 => $receipt) {
        //     $stock = Stock::where('goods_id', $receipt['id'])->available()->count();
        //     if ($receipt['qty'] < $stock) {
        //         session()->flash('warning', 'Sisa stok ' . $receipt['nama'] . ' tidak mencukupi (' . $stock . ')');
        //         return $this->render();
        //     }
        // }

        $receipt = collect($this->receipt)->map(function ($item, $index) {
            return collect($item['goods'])->map(function ($good) use ($item, $index) {
                return array_merge($good, [
                    'parent_description' => $item['description'], // Menambahkan description parent
                    'parent_index' => $index                      // Menambahkan index parent
                ]);
            });
        })->flatten(1);

        DB::transaction(function () use ($receipt) {
            $bill = $this->powerFee + $this->receiptFee + collect($receipt)->sum(fn($q) => $q['price'] * $q['qty']);
            
            $data = new Sale();
            $data->patient_id = $this->patient_id;
            $data->practitioner_id = $this->practitioner_id;
            $data->date = $this->date;
            $data->description = $this->description;
            $data->payment_description = $this->payment_description;
            $data->user_id = auth()->id();
            $data->amount = $bill;
            $data->power_fee = $this->powerFee;
            $data->receipt_fee = $this->receiptFee;
            $data->cash = $this->cash;
            $data->save();

            SaleDetail::insert(collect($receipt)->map(fn($q) => [
                'discount' => $q['discount'],
                'qty' => $q['qty'],
                'price' => $q['price'],
                'sale_id' => $data->id,
                'goods_id' => $q['id'],
                'total' => $q['total'],
                'office_portion' => $q['office_portion'],
                'receipt_description' => $q['parent_description'],
                'practitioner_id' => $this->practitioner_id,
                'receipt_no' => $q['parent_index'],
                'consignment_id' => $q['consignment_id'],
            ])->toArray());

            // foreach ($goods as $row) {
            //     Stock::where('goods_id', $row['id'])->available()->orderBy('created_at', 'asc')->limit($row['qty'])->update([
            //         'date_out_stock' => $this->date,
            //         'selling_price' => $row['price'],
            //         'discount' => $row['price'] * $row['discount'] / 100,
            //         'sale_id' => $data->id,
            //         'office_portion' => $row['office_portion'],
            //     ]);
            // }

            $data = Sale::findOrFail($data->id);
            $cetak = view('livewire.penjualan.cetak', [
                'cetak' => true,
                'data' => $data,
            ])->render();
            session()->flash('cetak', $cetak);
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('resep');
    }

    public function mount()
    {
        $this->date = $this->date ?: date('Y-m-d');
        $this->goodsData = Goods::orderBy('nama')->get()->toArray();
        $this->receipt = [
            [
                'description' => null,
                'goods' => [],
            ]
        ];
        $this->practitionerData = Practitioner::doctor()->with('employee')->orderBy('nama')->get()->map(fn($q) => [
            'id' => $q->id,
            'nama' => $q->nama ?: $q->employee->nama,
            'doctor' => $q->doctor == 1 ? 'Dokter' : '',
        ])->toArray();
    }

    public function addReceipt()
    {
        array_push(
            $this->receipt,
            [
                'description' => null,
                'goods' => [],
            ]
        );
        $this->total = collect($this->receipt)->pluck('goods')->flatten(1)->sum('total');
    }

    public function deleteReceipt($key)
    {
        unset($this->receipt[$key]);
        $this->receipt = array_merge($this->receipt);
        $this->total = collect($this->receipt)->pluck('goods')->flatten(1)->sum('total');
    }

    public function render()
    {
        return view('livewire.penjualan.resep.index');
    }
}
