<?php

namespace App\Livewire\Penjualan\Bebas;

use App\Models\Sale;
use App\Models\Goods;
use App\Models\Practitioner;
use App\Models\Stock;
use Livewire\Component;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $goodsData = [], $goods = [], $date, $description, $practitionerData = [], $practitioner_id, $type = "Cash", $cash = 0,  $total = 0, $payment_description, $patientData = [], $patient_id;

    public function addGoods()
    {
        array_push($this->goods, [
            'id' => null,
            'name' => null,
            'unit' => null,
            'price' => null,
            'qty' => 1,
            'discount' => 0,
            'total' => null,
            'consignment_id' => null,
            'office_portion' => null
        ]);
    }

    public function updatedGoods($value, $key)
    {
        $index = explode('.', $key);
        if ($index[1] == 'id') {
            $data = collect($this->goodsData)->where('id', $value)->first();
            $this->goods[$index[0]]['price'] = $data['price'] ?? 0;
            $this->goods[$index[0]]['name'] = $data['name'] ?? null;
            $this->goods[$index[0]]['unit'] = $data['unit'] ?? null;
            $this->goods[$index[0]]['consignment_id'] = $data['consignment_id'] ?? null;
            $this->goods[$index[0]]['capital'] = $data['consignment_id'] ? $data['capital'] : 0;
            $this->goods[$index[0]]['office_portion'] = $data['consignment_id'] ? $data['office_portion'] : 1;
            $this->goods[$index[0]]['practitioner_portion'] = $data['consignment_id'] ? $data['practitioner_portion'] : 0;
        }
        $this->goods[$index[0]]['total'] = ($this->goods[$index[0]]['price'] - ($this->goods[$index[0]]['price'] * ($this->goods[$index[0]]['discount'] ?? 0) / 100)) * ($this->goods[$index[0]]['qty'] ?? 0);
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
            'practitioner_id' => 'required',
            'date' => 'required|date',
            'goods' => 'required|array',
            'goods.*.id' => 'required',
            'goods.*.price' => 'required|integer',
            'goods.*.qty' => 'required',
        ]);

        DB::transaction(function () {

            $bill = collect($this->goods)->sum(fn($q) => $q['price'] * $q['qty']);
            
            $data = new Sale();
            $data->patient_id = $this->patient_id;
            $data->practitioner_id = $this->practitioner_id;
            $data->date = $this->date;
            $data->description = $this->description;
            $data->payment_description = $this->payment_description;
            $data->user_id = auth()->id();
            $data->amount = $bill;
            $data->cash = $this->cash;
            $data->save();

            SaleDetail::insert(collect($this->goods)->map(fn($q) => [
                'discount' => $q['discount'],
                'qty' => $q['qty'],
                'price' => $q['price'],
                'sale_id' => $data->id,
                'goods_id' => $q['id'],
                'consignment_id' => $q['consignment_id'],
                'practitioner_id' => $this->practitioner_id,
                'capital' => $q['capital'],
                'office_portion' => $q['office_portion'],
                'practitioner_portion' => $q['practitioner_portion'],
            ])->toArray());

            // foreach ($this->goods as $row) {
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
        $this->redirect('bebas');
    }

    public function mount()
    {
        $this->date = $this->date ?: date('Y-m-d');
        $this->goodsData = Goods::orderBy('name')->get()->toArray();
        $this->practitionerData = Practitioner::doctor()->with('employee')->orderBy('name')->get()->map(fn($q) => [
            'id' => $q->id,
            'name' => $q->name ?: $q->employee->name,
            'doctor' => $q->doctor == 1 ? 'Dokter' : '',
        ])->toArray();
    }

    public function render()
    {
        return view('livewire.penjualan.bebas.index');
    }
}
