<?php

namespace App\Livewire\Pengeluaran;

use Livewire\Component;
use App\Models\Expenditure;
use App\Models\MonthlyExpense;
use App\Models\ExpenditureDetail;
use Illuminate\Support\Facades\DB;

class Bulanan extends Component
{
    public $data, $previous, $detail = [];
    public $date, $uraian, $cost, $receipt;

    public function updatedDate()
    {
        $this->uraian = "Pengeluaran bulanan " . substr($this->date, 0, 7);
    }

    public function mount(Expenditure $data)
    {
        $this->previous = url()->previous();
        $this->date = $this->date ?: date('Y-m-d');
        $this->data = $data;
        $this->fill($this->data->toArray());
        if ($this->data->exists) {
            $this->detail = $this->data->expenditureDetail->map(fn($q) => [
                'uraian' => $q['uraian'],
                'cost' =>  $q['cost']
            ])->toArray();
        } else {
            $this->detail = MonthlyExpense::all()->map(fn($q) => [
                'uraian' => $q['nama'],
                'cost' => null
            ])->toArray();
        }
        $this->updatedDate();
    }

    public function submit()
    {

        $this->validate([
            'uraian' => 'required|unique:expenditures,uraian,'. $this->data->id,
            'date' => 'required',
        ]);

        DB::transaction(function () {
            $this->data->type = 'bulanan';
            $this->data->date = $this->date;
            $this->data->uraian = $this->uraian;
            $this->data->pengguna_id = auth()->id();
            $this->data->save();

            ExpenditureDetail::where('expenditure_id', $this->data->id)->delete();
            ExpenditureDetail::insert(collect($this->detail)->map(fn($q) => [
                'expenditure_id' => $this->data->id,
                'uraian' => $q['uraian'],
                'cost' => $q['cost']
            ])->toArray());

            session()->flash('success', 'Berhasil menyimpan data');
        });

        $this->redirect($this->previous);
    }

    public function render()
    {
        return view('livewire.pengeluaran.bulanan');
    }
}
