<?php

namespace App\Livewire\Manajemenstok\Pengadaan;

use App\Models\Barang;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Expenditure;
use App\Models\PurchaseDetail;
use App\Models\ExpenditureDetail;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $goodsData = [], $supplierData = [];
    public $date, $uraian, $due_date, $receipt, $supplier_id, $procurement = [], $cost = [], $status = "Jatuh Tempo", $ppn, $discount;

    public function addCost()
    {
        $this->cost[] = [
            'nama' => null,
            'qty' => 0,
            'harga' => null,
        ];
    }

    public function deleteCost($index)
    {
        unset($this->cost[$index]);
        $this->cost = array_merge($this->cost);
    }

    public function addProcurement()
    {
        $this->procurement[] = [
            'goods_id' => null,
            'qty' => 0,
            'expired_date' => null,
            'harga' => null,
        ];
    }

    public function deleteProcurement($index)
    {
        unset($this->procurement[$index]);
        $this->procurement = array_merge($this->procurement);
    }

    public function mount()
    {
        $this->date = $this->date ?: date('Y-m-d');
        $this->previous = url()->previous();
        $this->goodsData = Barang::orderBy('nama')->whereNull('consignment_id')->get()->toArray();
        $this->supplierData = Supplier::general()->orderBy('nama')->get()->toArray();
    }

    public function submit()
    {
        $this->validate([
            'receipt' => 'required|unique:purchases,receipt',
            'uraian' => 'required',
            'date' => 'required',
            'procurement' => 'required|array',
            'procurement.*.goods_id' => 'required|integer',
            'procurement.*.qty' => 'required|integer',
            'procurement.*.harga' => 'required|integer',
        ]);

        DB::transaction(function () {
            $data = new Purchase();
            $data->receipt = $this->receipt;
            $data->date = $this->date;
            $data->due_date = $this->status == "Jatuh Tempo" ? $this->due_date : null;
            $data->uraian = $this->uraian;
            $data->supplier_id = $this->status == "Opname" ? null : $this->supplier_id;
            $data->ppn = $this->ppn;
            $data->discount = $this->discount;
            $data->pengguna_id = auth()->id();
            $data->save();

            if ($this->status == "Lunas") {
                $expenditure = new Expenditure();
                $expenditure->type = 'form';
                $expenditure->date = $this->date;
                $expenditure->uraian = "Pengadaan Barang " . $data->uraian;
                $expenditure->receipt = $this->receipt;
                $expenditure->purchase_id = $this->data->id;
                $expenditure->pengguna_id = auth()->id();
                $expenditure->save();

                ExpenditureDetail::insert(collect($this->procurement)->map(fn($q) => [
                    'expenditure_id' => $expenditure->id,
                    'cost' => $q['harga'],
                    'uraian' => collect($this->goodsData)->where('id', $q['goods_id'])->first()->nama
                ])->toArray());
            }

            if (collect($this->procurement)->count() > 0) {
                PurchaseDetail::insert(collect($this->procurement)->map(fn($q) => [
                    'qty' => $q['qty'],
                    'harga' => $q['harga'],
                    'goods_id' => $q['goods_id'],
                    'purchase_id' => $data->id,
                ])->toArray());
            }
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function render()
    {
        return view('livewire.manajemenstok.pengadaan.form');
    }
}
