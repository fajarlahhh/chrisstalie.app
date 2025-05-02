<?php

namespace App\Livewire\Datamaster\Barang;

use App\Models\Goods;
use Livewire\Component;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $supplierData = [];
    public $name, $unit, $min_inventory, $price, $type, $description, $consignment_id, $precompounded, $kfa, $office_portion, $practitioner_portion, $capital;

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'unit' => 'required',
            'min_inventory' => 'required',
            'price' => 'required',
            'consignment_id' => 'integer|nullable',
            'type' => 'required',
        ]);

        if ($this->consignment_id) {
            $this->validate([
                'capital' => 'required',
                'practitioner_portion' => 'required',
                'office_portion' => 'required',
            ]);
        }

        DB::transaction(function () {
            $this->data->name = $this->name;
            $this->data->unit = $this->unit;
            $this->data->min_inventory = $this->min_inventory;
            $this->data->price = $this->price;
            $this->data->description = $this->description;
            $this->data->precompounded = $this->precompounded;
            $this->data->type = $this->type;
            $this->data->consignment_id = $this->consignment_id;
            $this->data->capital = $this->consignment_id ? $this->capital: null;
            $this->data->office_portion = $this->consignment_id ? $this->office_portion : 100;
            $this->data->practitioner_portion = $this->consignment_id ? $this->practitioner_portion: 0;
            $this->data->kfa = $this->kfa;
            $this->data->user_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(Goods $data)
    {
        $this->previous = url()->previous();
        $this->supplierData = Supplier::withoutGlobalScopes()->orderBy('name')->where('consignment', 1)->get()->toArray();
        $this->data = $data;
        $this->fill($this->data->toArray());
    }

    public function render()
    {
        return view('livewire.datamaster.barang.form');
    }
}
