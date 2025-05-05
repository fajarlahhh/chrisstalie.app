<?php

namespace App\Livewire\Penjualan\Bebas;

use App\Models\Sale;
use App\Models\Barang;
use App\Models\Nakes;
use App\Models\Stok;
use Livewire\Component;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $goodsData = [], $goods = [], $date, $uraian, $nakesData = [], $nakes_id, $type = "Cash", $cash = 0,  $total = 0, $payment_description, $pasienData = [], $pasien_id;

    public function addGoods()
    {
        array_push($this->goods, [
            'id' => null,
            'nama' => null,
            'satuan' => null,
            'harga' => null,
            'qty' => 1,
            'discount' => 0,
            'total' => null,
            'consignment_id' => null,
            'porsi_kantor' => null
        ]);
    }

    public function updatedGoods($value, $key)
    {
        $index = explode('.', $key);
        if ($index[1] == 'id') {
            $data = collect($this->goodsData)->where('id', $value)->first();
            $this->goods[$index[0]]['harga'] = $data['harga'] ?? 0;
            $this->goods[$index[0]]['nama'] = $data['nama'] ?? null;
            $this->goods[$index[0]]['satuan'] = $data['satuan'] ?? null;
            $this->goods[$index[0]]['consignment_id'] = $data['consignment_id'] ?? null;
            $this->goods[$index[0]]['modal'] = $data['consignment_id'] ? $data['modal'] : 0;
            $this->goods[$index[0]]['porsi_kantor'] = $data['consignment_id'] ? $data['porsi_kantor'] : 1;
            $this->goods[$index[0]]['porsi_nakes'] = $data['consignment_id'] ? $data['porsi_nakes'] : 0;
        }
        $this->goods[$index[0]]['total'] = ($this->goods[$index[0]]['harga'] - ($this->goods[$index[0]]['harga'] * ($this->goods[$index[0]]['discount'] ?? 0) / 100)) * ($this->goods[$index[0]]['qty'] ?? 0);
        $this->total = collect($this->goods)->sum('total');
    }

    public function deleteGoods($key)
    {
        unset($this->goods[$key]);
        $this->goods = array_merge($this->goods);
    }

    public function submit()
    {
        $this->validate([
            'nakes_id' => 'required',
            'date' => 'required|date',
            'goods' => 'required|array',
            'goods.*.id' => 'required',
            'goods.*.harga' => 'required|integer',
            'goods.*.qty' => 'required',
        ]);

        DB::transaction(function () {

            $bill = collect($this->goods)->sum(fn($q) => $q['harga'] * $q['qty']);
            
            $data = new Sale();
            $data->pasien_id = $this->pasien_id;
            $data->nakes_id = $this->nakes_id;
            $data->date = $this->date;
            $data->uraian = $this->uraian;
            $data->payment_description = $this->payment_description;
            $data->user_id = auth()->id();
            $data->amount = $bill;
            $data->cash = $this->cash;
            $data->save();

            SaleDetail::insert(collect($this->goods)->map(fn($q) => [
                'discount' => $q['discount'],
                'qty' => $q['qty'],
                'harga' => $q['harga'],
                'sale_id' => $data->id,
                'goods_id' => $q['id'],
                'consignment_id' => $q['consignment_id'],
                'nakes_id' => $this->nakes_id,
                'modal' => $q['modal'],
                'porsi_kantor' => $q['porsi_kantor'],
                'porsi_nakes' => $q['porsi_nakes'],
            ])->toArray());

            // foreach ($this->goods as $row) {
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
        $this->redirect('bebas');
    }

    public function mount()
    {
        $this->date = $this->date ?: date('Y-m-d');
        $this->goodsData = Barang::orderBy('nama')->get()->toArray();
        $this->nakesData = Nakes::dokter()->with('pegawai')->orderBy('nama')->get()->map(fn($q) => [
            'id' => $q->id,
            'nama' => $q->nama ?: $q->pegawai->nama,
            'dokter' => $q->dokter == 1 ? 'Dokter' : '',
        ])->toArray();
    }

    public function render()
    {
        return view('livewire.penjualan.bebas.index');
    }
}
