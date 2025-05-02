<?php

namespace App\Livewire\Datamaster\Tariftindakan;

use Livewire\Component;
use App\Models\ActionRate;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $capital;
    public $nama, $price, $practitioner_portion, $office_portion_percent, $category, $profit, $beautician_fee, $description, $icd_9_cm_code;

    public function submit()
    {
        $this->validate([
            'capital' => 'required',
            'category' => 'required',
            'nama' => 'required',
            'office_portion_percent' => 'required',
            'price' => 'required',
        ]);

        DB::transaction(function () {
            $this->data->nama = $this->nama;
            $this->data->category = $this->category;
            $this->data->description = $this->description;
            $this->data->price = $this->price;
            $this->data->capital = $this->capital;
            $this->data->beautician_fee = $this->beautician_fee;
            $this->data->profit = $this->price - $this->capital;
            $this->data->office_portion = (($this->price - $this->capital - $this->beautician_fee) * $this->office_portion_percent) / 100;
            $this->data->practitioner_portion = (($this->price - $this->capital - $this->beautician_fee) * (100 - $this->office_portion_percent)) / 100;
            $this->data->percent_practitioner = 1 - ($this->office_portion_percent / 100);
            $this->data->percent_office = $this->office_portion_percent / 100;
            $this->data->icd_9_cm_code = $this->icd_9_cm_code;
            $this->data->user_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(ActionRate $data)
    {
        $this->previous = url()->previous();
        $this->data = $data;
        $this->fill($this->data->toArray());
        if ($data->exists) {
            $this->office_portion_percent = $this->data->office_portion / ($this->price - $this->capital - $this->beautician_fee) * 100;
        }
    }

    public function render()
    {
        return view('livewire.datamaster.tariftindakan.form');
    }
}
