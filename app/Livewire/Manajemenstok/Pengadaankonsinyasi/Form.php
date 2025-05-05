<?php

namespace App\Livewire\Manajemenstok\Pengadaankonsinyasi;

use App\Models\Barang;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\PurchaseDetail;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $goodsData = [], $supplierData = [];
    public $date, $uraian, $due_date, $receipt, $supplier_id, $procurement = [], $cost = [];

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
        $this->previous = url()->previous();
        $this->date = $this->date ?: date('Y-m-d');
        $this->supplierData = Supplier::konsinyasi()->orderBy('nama')->get()->toArray();
    }

    public function updatedSupplierId()
    {
        $this->goodsData = Barang::orderBy('nama')->where('consignment_id', $this->supplier_id)->get()->toArray();
    }

    public function submit()
    {
        $this->validate([
            'uraian' => 'required',
            'supplier_id' => 'required',
            'date' => 'required',
            'procurement' => 'required|array',
            'procurement.*.goods_id' => 'required|integer',
            'procurement.*.qty' => 'required|integer',
        ]);
        DB::transaction(function () {
            $data = new Purchase();
            $data->receipt = now();
            $data->date = $this->date;
            $data->konsinyasi = 1;
            $data->uraian = $this->uraian;
            $data->supplier_id = $this->supplier_id;
            $data->user_id = auth()->id();
            $data->save();

            if (collect($this->procurement)->count() > 0) {
                PurchaseDetail::insert(collect($this->procurement)->map(fn($q) => [
                    'qty' => $q['qty'],
                    'harga' => Barang::find($q['goods_id'])->modal,
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
        return view('livewire.manajemenstok.pengadaankonsinyasi.form');
    }
}
