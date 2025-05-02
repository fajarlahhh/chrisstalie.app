<?php

namespace App\Livewire\Datamaster\Supplier;

use Livewire\Component;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous;
    public $nama, $description, $address, $phone, $consignment = false;

    public function submit()
    {
        $this->validate([
            'nama' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        DB::transaction(function () {
            $this->data->nama = $this->nama;
            $this->data->description = $this->description;
            $this->data->address = $this->address;
            $this->data->phone = $this->phone;
            $this->data->consignment = $this->consignment ? 1 : 0;
            $this->data->user_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(Supplier $data)
    {
        $this->previous = url()->previous();
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->consignment = $this->data->consignment == 1 ? true : false;
    }

    public function render()
    {
        return view('livewire.datamaster.supplier.form');
    }
}
