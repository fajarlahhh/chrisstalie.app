<?php

namespace App\Livewire\Pengeluaran;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Expenditure;
use App\Models\ExpenditureDetail;
use App\Models\MonthlyExpense;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $expenditureData = [];
    public $date, $monthly_expenses_id, $description, $cost, $receipt, $office, $expenditure_type ;

    public function mount(Expenditure $data)
    {
        $this->expenditureData = MonthlyExpense::orderBy('nama')->get()->toArray();
        $this->previous = url()->previous();
        $this->date = $this->date ?: date('Y-m-d');
        $this->data = $data;
        if ($this->data->exists) {
            $this->fill($this->data->toArray());
        }
    }

    public function submit()
    {
        $this->validate([
            'receipt' => 'required',
            'date' => 'required',
            'cost' => 'required',
            'expenditure_type' => 'required',
            'office' => 'required',
        ]);
        
        DB::transaction(function () {
            $this->data->type = 'form';
            $this->data->date = $this->date;
            // $this->data->employee_id = $this->employee_id;
            $this->data->description = $this->monthly_expenses_id ?: $this->description;
            $this->data->receipt = $this->receipt;
            $this->data->expenditure_type = $this->expenditure_type;
            $this->data->cost = $this->cost;
            $this->data->office = $this->office;
            $this->data->user_id = auth()->id();
            $this->data->save();

            // ExpenditureDetail::where('expenditure_id', $this->data->id)->delete();
            // ExpenditureDetail::insert([
            //     'expenditure_id' => $this->data->id,
            //     'description' => $this->description,
            //     'cost' => $this->cost
            // ]);

            session()->flash('success', 'Berhasil menyimpan data');
        });

        $this->redirect($this->previous);
    }

    public function render()
    {
        return view('livewire.pengeluaran.form');
    }
}
