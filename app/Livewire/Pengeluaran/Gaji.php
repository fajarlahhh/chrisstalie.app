<?php

namespace App\Livewire\Pengeluaran;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Expenditure;
use App\Models\ExpenditureDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Gaji extends Component
{
    public $data, $previous, $detail = [], $other = ['Uang Makan', 'Jasa Pelayanan', 'Bonus'], $otherCost = [];
    public $date, $description, $cost, $receipt;

    public function updatedDate()
    {
        $this->description = "Gaji bulan " . substr($this->date, 0, 7);
    }

    public function mount(Expenditure $data)
    {
        $this->previous = url()->previous();
        $this->date = $this->date ?: date('Y-m-d');
        $this->data = $data;
        $this->updatedDate();
        $this->detail = Employee::all()->map(fn($q) => [
            'id' => $q->id,
            'nik' => $q->nik,
            'nama' => $q->nama,
            'wages' => $q->wages,
            'allowance' => $q->allowance,
            'transport_allowance' => $q->transport_allowance,
            'bpjs_health_cost' => $q->bpjs_health_cost
        ]);
        foreach ($this->detail as $key => $row) {
            $this->otherCost[] = [
                'id' => $row['id'],
                'nik' => $row['nik'],
                'nama' => $row['nama'],
                'uang_makan' => 0,
                'jasa_pelayanan' => 0,
                'bonus' => 0
            ];
        }
    }

    public function submit()
    {
        $this->validate([
            'date' => 'required',
        ]);

        DB::transaction(function () {
            $expenditure = [];
            $now = Carbon::now();
            foreach ($this->detail as $key => $row) {
                $expenditure[] = [
                    'date' => $this->date,
                    'user_id' => auth()->id(),
                    'type' => 'gaji',
                    'description' => 'Gaji ' . $row['nama'],
                    'employee_id' => $row['id'],
                    'cost' => $row['wages'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $expenditure[] = [
                    'date' => $this->date,
                    'user_id' => auth()->id(),
                    'type' => 'gaji',
                    'description' => 'Tunjangan ' . $row['nama'],
                    'employee_id' => $row['id'],
                    'cost' => $row['allowance'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $expenditure[] = [
                    'date' => $this->date,
                    'user_id' => auth()->id(),
                    'type' => 'gaji',
                    'description' => 'Tunjangan Transport ' . $row['nama'],
                    'employee_id' => $row['id'],
                    'cost' => $row['allowance'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $expenditure[] = [
                    'date' => $this->date,
                    'user_id' => auth()->id(),
                    'type' => 'gaji',
                    'description' => 'BPJS Kesehatan ' . $row['nama'],
                    'employee_id' => $row['id'],
                    'cost' => $row['bpjs_health_cost'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            foreach ($this->otherCost as $key => $row) {
                $expenditure[] = [
                    'date' => $this->date,
                    'user_id' => auth()->id(),
                    'type' => 'gaji',
                    'description' => 'Uang Makan ' . $row['nama'],
                    'employee_id' => $row['id'],
                    'cost' => $row['uang_makan'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $expenditure[] = [
                    'date' => $this->date,
                    'user_id' => auth()->id(),
                    'type' => 'gaji',
                    'description' => 'Jasa Pelayanan ' . $row['nama'],
                    'employee_id' => $row['id'],
                    'cost' => $row['jasa_pelayanan'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $expenditure[] = [
                    'date' => $this->date,
                    'user_id' => auth()->id(),
                    'type' => 'gaji',
                    'description' => 'Bonus ' . $row['nama'],
                    'employee_id' => $row['id'],
                    'cost' => $row['bonus'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            Expenditure::insert($expenditure);

            session()->flash('success', 'Berhasil menyimpan data');
        });

        $this->redirect($this->previous);
    }

    public function render()
    {
        return view('livewire.pengeluaran.gaji');
    }
}
