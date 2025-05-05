<?php

namespace App\Livewire\Pelayanan\Kasir;

use App\Models\Sale;
use App\Models\Barang;
use App\Models\Stok;
use App\Models\Payment;
use Livewire\Component;
use App\Models\Tarif;
use App\Models\SaleDetail;
use App\Models\Nakes;
use App\Models\Registration;
use App\Models\PaymentTreatment;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentToolMaterial;

class Form extends Component
{
    public $date, $data, $dataTarif = [], $dataNakes = [], $treatment = [], $toolsAndMaterial = [], $dataGoods = [];
    public $adminFee = 10000, $type = "Cash", $cash, $remainder;

    public function mount(Registration $data)
    {
        $this->dataNakes = Nakes::with('pegawai')->orderBy('nama')->get()->toArray();
        $this->dataTarif = Tarif::orderBy('nama')->get()->toArray();
        $this->dataGoods = Barang::orderBy('nama')->get()->toArray();
        $this->date = $this->date ?: date('Y-m-d');
        $this->data = $data;
        $this->treatment = $data->treatment->map(function ($q) {
            $dataTarif = collect($this->dataTarif)->where('id', $q->action_rate_id)->first();
            return [
                'action_rate_id' => $q->action_rate_id,
                'discount' => 0,
                'qty' => $q->qty,
                'harga' => $dataTarif['harga'],
                'keuntungan' => $dataTarif['keuntungan'],
                'modal' => $dataTarif['modal'],
                'porsi_kantor' => $dataTarif['percent_office'],
                'porsi_nakes' => $dataTarif['percent_nakes'],
                'upah_petugas' => $dataTarif['upah_petugas'],
                'nakes_id' => $q->nakes_id,
                'beautician_id' => $q->beautician_id
            ];
        })->toArray();
        $this->toolsAndMaterial = $data->toolMaterial->map(function ($q) {
            $dataGoods = collect($this->dataGoods)->where('id', $q->goods_id)->first();
            return [
                'goods_id' => $q->goods_id,
                'discount' => 0,
                'qty' => $q->qty,
                'harga' => $dataGoods['harga'],
                'consignment_id' => $dataGoods['consignment_id'],
                'modal' => $dataGoods['modal'],
                'porsi_nakes' => $dataGoods['porsi_nakes'],
                'porsi_kantor' => $dataGoods['porsi_kantor']
            ];
        })->toArray();
    }

    public function submit()
    {
        $this->validate([
            'type' => 'required',
        ]);

        $bill = $this->adminFee + collect($this->treatment)->sum(fn($q) => ($q['harga'] - (($q['discount'] ?: 0) / 100) * $q['harga']) * $q['qty']) + collect($this->toolsAndMaterial)->sum(fn($q) => ($q['harga'] - (($q['discount'] ?: 0) / 100) * $q['harga']) * $q['qty']);

        if ($this->type == "Cash") {
            $this->validate([
                'cash' => 'required|numeric|min:' . $bill,
            ]);
        }

        if (collect($this->toolsAndMaterial)->count() > 0) {
            $this->validate([
                // 'toolsAndMaterial.*.goods_id' => ['required', function ($attribute, $value, $fail) {
                //     $data = explode('.', $attribute);
                //     $stok = Stok::where('goods_id', $value)->available()->count();
                //     if ($this->toolsAndMaterial[$data[1]]['qty'] > $stok) {
                //         $fail("There are $stok left in stok");
                //     }
                // }],
                'toolsAndMaterial.*.harga' => 'required|integer',
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
                'harga' => $q['harga'],
                'keuntungan' => $q['keuntungan'],
                'discount' => $q['discount'],
                'modal' => $q['modal'],
                'porsi_kantor' => $q['porsi_kantor'],
                'porsi_nakes' => $q['porsi_nakes'],
                'upah_petugas' => $q['upah_petugas'],
                'nakes_id' => $q['nakes_id'],
                'beautician_id' => $q['beautician_id'],
                'action_rate_id' => $q['action_rate_id'],
                'payment_id' => $payment->id,
            ])->toArray());

            if (collect($this->toolsAndMaterial)->count() > 0) {
                $sale = new Sale();
                $sale->payment_id = $payment->id;
                $sale->pasien_id = $this->data->pasien_id;
                $sale->date = $this->date;
                $sale->amount = collect($this->toolsAndMaterial)->sum(fn($q) => $q['harga'] * $q['qty']);
                $sale->date = $this->date;
                $sale->user_id = auth()->id();
                $sale->save();

                SaleDetail::insert(collect($this->toolsAndMaterial)->map(
                    fn($q) =>
                    [
                        'discount' => $q['discount'],
                        'qty' => $q['qty'],
                        'harga' => $q['harga'],
                        'sale_id' => $sale->id,
                        'consignment_id' => $q['consignment_id'],
                        'modal' => $q['modal'],
                        'porsi_kantor' => $q['porsi_kantor'],
                        'porsi_nakes' => $q['porsi_nakes'],
                        'goods_id' => $q['goods_id'],
                    ]
                )->toArray());
            }


            // foreach ($this->toolsAndMaterial as $row) {
            //     Stok::where('goods_id', $row['goods_id'])->available()->orderBy('created_at', 'asc')->limit($row['qty'])->update([
            //         'date_out_stok' => $this->date,
            //         'selling_harga' => $row['harga'],
            //         'sale_id' => $sale->id,
            //         'discount' => $row['harga'] * $row['discount'] / 100,
            //         'porsi_kantor' => $row['porsi_kantor'],
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
