<?php

namespace App\Livewire\Datamaster\Pengeluaran;

use App\Models\Office;
use Livewire\Component;
use App\Models\MonthlyExpense;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $officeData = [];
    public $nama, $uraian, $office;

    public function submit()
    {
        $this->validate([
            'nama' => 'required',
            'office' => 'required',
        ]);

        DB::transaction(function () {
            $this->data->nama = $this->nama;
            $this->data->office = $this->office;
            $this->data->uraian = $this->uraian;
            $this->data->user_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(MonthlyExpense $data)
    {
        $this->previous = url()->previous();
        $this->data = $data;
        $this->fill($this->data->toArray());
    }

    public function render()
    {
        return view('livewire.datamaster.pengeluaran.form');
    }
}
