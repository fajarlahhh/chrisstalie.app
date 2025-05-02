<?php

namespace App\Livewire\Manajemenstok\Pengadaankonsinyasi;

use App\Models\Goods;
use Livewire\Component;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\PurchaseDetail;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $goodsData = [], $supplierData = [];
    public $date, $description, $due_date, $receipt, $supplier_id, $procurement = [], $cost = [];

    public function addCost()
    {
        $this->cost[] = [
            'name' => null,
            'qty' => 0,
            'price' => null,
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
            'price' => null,
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
        $this->supplierData = Supplier::consignment()->orderBy('name')->get()->toArray();
    }

    public function updatedSupplierId()
    {
        $this->goodsData = Goods::orderBy('name')->where('consignment_id', $this->supplier_id)->get()->toArray();
    }

    public function submit()
    {
        $this->validate([
            'description' => 'required',
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
            $data->consignment = 1;
            $data->description = $this->description;
            $data->supplier_id = $this->supplier_id;
            $data->user_id = auth()->id();
            $data->save();

            if (collect($this->procurement)->count() > 0) {
                PurchaseDetail::insert(collect($this->procurement)->map(fn($q) => [
                    'qty' => $q['qty'],
                    'price' => Goods::find($q['goods_id'])->capital,
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
