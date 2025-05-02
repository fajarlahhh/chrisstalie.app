<?php

namespace App\Livewire\Pelayanan\Kasir;

use App\Models\Sale;
use App\Models\Goods;
use App\Models\Stock;
use App\Models\Payment;
use Livewire\Component;
use App\Models\ActionRate;
use App\Models\SaleDetail;
use App\Models\Practitioner;
use App\Models\Registration;
use App\Models\PaymentTreatment;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentToolMaterial;

class Form extends Component
{
    public $date, $data, $dataActionRate = [], $dataPractitioner = [], $treatment = [], $toolsAndMaterial = [], $dataGoods = [];
    public $adminFee = 10000, $type = "Cash", $cash, $remainder;

    public function mount(Registration $data)
    {
        $this->dataPractitioner = Practitioner::with('employee')->orderBy('name')->get()->toArray();
        $this->dataActionRate = ActionRate::orderBy('name')->get()->toArray();
        $this->dataGoods = Goods::orderBy('name')->get()->toArray();
        $this->date = $this->date ?: date('Y-m-d');
        $this->data = $data;
        $this->treatment = $data->treatment->map(function ($q) {
            $dataActionRate = collect($this->dataActionRate)->where('id', $q->action_rate_id)->first();
            return [
                'action_rate_id' => $q->action_rate_id,
                'discount' => 0,
                'qty' => $q->qty,
                'price' => $dataActionRate['price'],
                'profit' => $dataActionRate['profit'],
                'capital' => $dataActionRate['capital'],
                'office_portion' => $dataActionRate['percent_office'],
                'practitioner_portion' => $dataActionRate['percent_practitioner'],
                'beautician_fee' => $dataActionRate['beautician_fee'],
                'practitioner_id' => $q->practitioner_id,
                'beautician_id' => $q->beautician_id
            ];
        })->toArray();
        $this->toolsAndMaterial = $data->toolMaterial->map(function ($q) {
            $dataGoods = collect($this->dataGoods)->where('id', $q->goods_id)->first();
            return [
                'goods_id' => $q->goods_id,
                'discount' => 0,
                'qty' => $q->qty,
                'price' => $dataGoods['price'],
                'consignment_id' => $dataGoods['consignment_id'],
                'capital' => $dataGoods['capital'],
                'practitioner_portion' => $dataGoods['practitioner_portion'],
                'office_portion' => $dataGoods['office_portion']
            ];
        })->toArray();
    }

    public function submit()
    {
        $this->validate([
            'type' => 'required',
        ]);

        $bill = $this->adminFee + collect($this->treatment)->sum(fn($q) => ($q['price'] - (($q['discount'] ?: 0) / 100) * $q['price']) * $q['qty']) + collect($this->toolsAndMaterial)->sum(fn($q) => ($q['price'] - (($q['discount'] ?: 0) / 100) * $q['price']) * $q['qty']);

        if ($this->type == "Cash") {
            $this->validate([
                'cash' => 'required|numeric|min:' . $bill,
            ]);
        }

        if (collect($this->toolsAndMaterial)->count() > 0) {
            $this->validate([
                // 'toolsAndMaterial.*.goods_id' => ['required', function ($attribute, $value, $fail) {
                //     $data = explode('.', $attribute);
                //     $stok = Stock::where('goods_id', $value)->available()->count();
                //     if ($this->toolsAndMaterial[$data[1]]['qty'] > $stok) {
                //         $fail("There are $stok left in stock");
                //     }
                // }],
                'toolsAndMaterial.*.price' => 'required|integer',
                'toolsAndMaterial.*.qty' => 'required|integer',
            ]);
        }

        DB::transaction(function () use ($bill) {
            $payment = new Payment();
            $payment->date = $this->date;
            $payment->type = $this->type;
            $payment->admin = $this->adminFee;
            $payment->amount = $bill;
            $payment->cash = $this->cash;
            $payment->registration_id = $this->data->id;
            $payment->user_id = auth()->id();
            $payment->save();

            PaymentTreatment::insert(collect($this->treatment)->map(fn($q) => [
                'qty' => $q['qty'],
                'price' => $q['price'],
                'profit' => $q['profit'],
                'discount' => $q['discount'],
                'capital' => $q['capital'],
                'office_portion' => $q['office_portion'],
                'practitioner_portion' => $q['practitioner_portion'],
                'beautician_fee' => $q['beautician_fee'],
                'practitioner_id' => $q['practitioner_id'],
                'beautician_id' => $q['beautician_id'],
                'action_rate_id' => $q['action_rate_id'],
                'payment_id' => $payment->id,
            ])->toArray());

            if (collect($this->toolsAndMaterial)->count() > 0) {
                $sale = new Sale();
                $sale->payment_id = $payment->id;
                $sale->patient_id = $this->data->patient_id;
                $sale->date = $this->date;
                $sale->amount = collect($this->toolsAndMaterial)->sum(fn($q) => $q['price'] * $q['qty']);
                $sale->date = $this->date;
                $sale->user_id = auth()->id();
                $sale->save();

                SaleDetail::insert(collect($this->toolsAndMaterial)->map(
                    fn($q) =>
                    [
                        'discount' => $q['discount'],
                        'qty' => $q['qty'],
                        'price' => $q['price'],
                        'sale_id' => $sale->id,
                        'consignment_id' => $q['consignment_id'],
                        'capital' => $q['capital'],
                        'office_portion' => $q['office_portion'],
                        'practitioner_portion' => $q['practitioner_portion'],
                        'goods_id' => $q['goods_id'],
                    ]
                )->toArray());
            }


            // foreach ($this->toolsAndMaterial as $row) {
            //     Stock::where('goods_id', $row['goods_id'])->available()->orderBy('created_at', 'asc')->limit($row['qty'])->update([
            //         'date_out_stock' => $this->date,
            //         'selling_price' => $row['price'],
            //         'sale_id' => $sale->id,
            //         'discount' => $row['price'] * $row['discount'] / 100,
            //         'office_portion' => $row['office_portion'],
            //     ]);
            // }

            Registration::where('id', $this->data->id)->update(['go_home' => now()]);
            
            $cetak = view('livewire.pelayanan.kasir.cetak', [
                'cetak' => true,
                'data' => Payment::findOrFail($payment->id),
            ])->render();
            session()->flash('cetak', $cetak);
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pelayanan/kasir');
    }

    public function render()
    {
        return view('livewire.pelayanan.kasir.form');
    }
}
