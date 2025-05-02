<?php

namespace App\Livewire\Gaji;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Expenditure;
use App\Models\ExpenditureDetail;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $employeeData = [], $detail = ['Uang Makan' => 0, 'Jasa Pelayanan' => 0, 'Bonus' => 0], $otherCost = [], $employee;
    public $date, $description, $cost, $receipt, $month, $year, $employee_id;

    public function updatedDate()
    {
        $this->description = "Gaji bulan " . substr($this->date, 0, 7);
    }

    public function mount(Expenditure $data)
    {
        $this->previous = url()->previous();
        $this->month = $this->month ?: date('m');
        $this->year = $this->year ?: date('Y');
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->employeeData = Employee::orderBy('name')->get()->toArray();
        if ($this->data->exists) {
            $this->detail = $this->data->expenditureDetail->map(fn($q) => [
                'jenis' => $q['description'],
                'cost' =>  $q['cost']
            ]);
            $this->employee = collect($this->employeeData)->where('id', $this->data->employee_id)->first();
        }
    }

    public function updatedEmployeeId()
    {
        $this->employee = collect($this->employeeData)->where('id', $this->employee_id)->first();
        $this->detail = [
            [
                'jenis' => ' Gaji',
                'cost' => $this->employee['wages']
            ],
            [
                'jenis' => '+ Tunjangan',
                'cost' => $this->employee['allowance']
            ],
            [
                'jenis' => '+ Transport',
                'cost' => $this->employee['transport_allowance']
            ],
            [
                'jenis' => '+ Uang Makan',
                'cost' => 0
            ],
            [
                'jenis' => '+ Jasa Pelayanan',
                'cost' => 0
            ],
            [
                'jenis' => '+ Bonus',
                'cost' => 0
            ],
            [
                'jenis' => '- BPJS',
                'cost' => $this->employee['bpjs_health_cost']
            ],
        ];
    }

    public function submit()
    {
        $this->validate([
            'date' => 'required',
            'month' => 'required',
            'year' => 'required',
            'employee_id' => 'required',
        ]);

        DB::transaction(function () {
            $total = 0;
            foreach ($this->detail as $key => $row) {
                if (strpos($row['jenis'], '-') !== false) {
                    $total -= (int)$row['cost'];
                } else {
                    $total += (int)$row['cost'];
                }
            }

            $this->data->type = 'gaji';
            $this->data->date = $this->date;
            $this->data->cost = $total;
            $this->data->employee_id = $this->employee_id;
            $this->data->description = "Gaji " . $this->employee['name'] . ' bulan ' . $this->month . '-' . $this->year;
            $this->data->user_id = auth()->id();
            $this->data->save();

            ExpenditureDetail::where('expenditure_id', $this->data->id)->delete();
            ExpenditureDetail::insert(collect($this->detail)->map(fn($q, $index) => [
                'description' => $q['jenis'],
                'cost' => $q['cost'],
                'expenditure_id' => $this->data->id
            ])->toArray());

            session()->flash('success', 'Berhasil menyimpan data');
        });

        $this->redirect($this->previous);
        return redirect()->to($this->previous);
    }

    public function render()
    {
        return view('livewire.gaji.form');
    }
}
