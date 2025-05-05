<?php

namespace App\Livewire\Penjualan\Resep;

use App\Models\Sale;
use App\Models\Barang;
use App\Models\Stok;
use App\Models\Pasien;
use Livewire\Component;
use App\Models\SaleDetail;
use App\Models\Nakes;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $pasien_id, $pasien, $goodsData = [],  $date, $uraian, $powerFee = 2000, $receiptFee = 3000,  $receipt = [], $type = "Cash", $cash = 0,  $total = 0, $payment_description, $nakes_id, $nakesData = [];

    public function addGoods($index)
    {
        $this->receipt[$index]['goods'][] =
            [
                'id' => null,
                'nama' => null,
                'satuan' => null,
                'harga' => 0,
                'qty' => 1,
                'discount' => 0,
                'total' => 0,
                'porsi_kantor' => null,
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
            $this->receipt[$index1]['goods'][$index2]['harga'] = $data['harga'] ?? 0;
            $this->receipt[$index1]['goods'][$index2]['nama'] = $data['nama'] ?? null;
            $this->receipt[$index1]['goods'][$index2]['satuan'] = $data['satuan'] ?? null;
            $this->receipt[$index1]['goods'][$index2]['porsi_kantor'] = $data['porsi_kantor'] ?? null;
            $this->receipt[$index1]['goods'][$index2]['consignment_id'] = $data['consignment_id'] ?? null;
            $this->receipt[$index1]['goods'][$index2]['modal'] = $data['consignment_id'] ? $data['modal'] : 0;
            $this->receipt[$index1]['goods'][$index2]['porsi_kantor'] = $data['consignment_id'] ? $data['porsi_kantor'] : 1;
            $this->receipt[$index1]['goods'][$index2]['porsi_nakes'] = $data['consignment_id'] ? $data['porsi_nakes'] : 0;
        }
        $qty = (float)$this->receipt[$index1]['goods'][$index2]['qty'];
        $discount = (float)$this->receipt[$index1]['goods'][$index2]['discount'];
        $this->receipt[$index1]['goods'][$index2]['total'] = ($this->receipt[$index1]['goods'][$index2]['harga'] - ($this->receipt[$index1]['goods'][$index2]['harga'] * ($discount ?? 0) / 100)) * ($qty ?? 0);
        $this->total = collect($this->receipt)->pluck('goods')->flatten(1)->sum('total');
    }

    public function submit()
    {
        $this->validate([
            'nakes_id' => 'required',
            'date' => 'required|date',
            'receipt' => 'required|array',
            'cash' => 'required|numeric|min:' . $this->total,
        ]);

        // $goods = collect($this->receipt)->pluck('goods')->flatten(1)->groupBy('id')->map(fn($q) => [
        //     'id' => $q->first()['id'],
        //     'nama' => $q->first()['nama'],
        //     'satuan' => $q->first()['satuan'],
        //     'qty' => $q->sum('qty'),
        //     'harga' => $q->first()['harga'],
        //     'discount' => $q->first()['discount'],
        //     'total' => $q->sum(fn($q) => $q['qty'] * ($q['harga'] - ($q['harga'] * $q['discount'] / 100))),
        //     'porsi_kantor' => $q->first()['porsi_kantor'],

        // ]);

        // foreach ($goods as $key1 => $receipt) {
        //     $stok = Stok::where('goods_id', $receipt['id'])->available()->count();
        //     if ($receipt['qty'] < $stok) {
        //         session()->flash('warning', 'Sisa stok ' . $receipt['nama'] . ' tidak mencukupi (' . $stok . ')');
        //         return $this->render();
        //     }
        // }

        $receipt = collect($this->receipt)->map(function ($item, $index) {
            return collect($item['goods'])->map(function ($good) use ($item, $index) {
                return array_merge($good, [
                    'parent_description' => $item['uraian'], // Menambahkan uraian parent
                    'parent_index' => $index                      // Menambahkan index parent
                ]);
            });
        })->flatten(1);

        DB::transaction(function () use ($receipt) {
            $bill = $this->powerFee + $this->receiptFee + collect($receipt)->sum(fn($q) => $q['harga'] * $q['qty']);
            
            $data = new Sale();
            $data->pasien_id = $this->pasien_id;
            $data->nakes_id = $this->nakes_id;
            $data->date = $this->date;
            $data->uraian = $this->uraian;
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
                'harga' => $q['harga'],
                'sale_id' => $data->id,
                'goods_id' => $q['id'],
                'total' => $q['total'],
                'porsi_kantor' => $q['porsi_kantor'],
                'receipt_description' => $q['parent_description'],
                'nakes_id' => $this->nakes_id,
                'receipt_no' => $q['parent_index'],
                'consignment_id' => $q['consignment_id'],
            ])->toArray());

            // foreach ($goods as $row) {
            //     Stok::where('goods_id', $row['id'])->available()->orderBy('created_at', 'asc')->limit($row['qty'])->update([
            //         'date_out_stok' => $this->date,
            //         'selling_harga' => $row['harga'],
            //         'discount' => $row['harga'] * $row['discount'] / 100,
            //         'sale_id' => $data->id,
            //         'porsi_kantor' => $row['porsi_kantor'],
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
        $this->goodsData = Barang::orderBy('nama')->get()->toArray();
        $this->receipt = [
            [
                'uraian' => null,
                'goods' => [],
            ]
        ];
        $this->nakesData = Nakes::dokter()->with('pegawai')->orderBy('nama')->get()->map(fn($q) => [
            'id' => $q->id,
            'nama' => $q->nama ?: $q->pegawai->nama,
            'dokter' => $q->dokter == 1 ? 'Dokter' : '',
        ])->toArray();
    }

    public function addReceipt()
    {
        array_push(
            $this->receipt,
            [
                'uraian' => null,
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
