<?php

namespace App\Livewire\Pelayanan\Diagnosis;

use App\Models\Diagnosis;
use App\Models\Icd10;
use Livewire\Component;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $date, $data, $dataIcd10 = [], $diagnosis = [];

    public function mount(Registration $data)
    {
        $this->date = $this->date ?: date('Y-m-d');
        $this->data = $data;
        $this->dataIcd10 = Icd10::orderBy('description')->get()->toArray();
        $this->diagnosis = $data->diagnosis->map(fn($q) => ['icd10' => $q->icd10_id])->toArray();
    }

    public function addDiagnosis()
    {
        $this->diagnosis[] = ['icd10' => null];
    }

    public function deleteDiagnosis($index)
    {
        unset($this->diagnosis[$index]);
        $this->diagnosis = array_merge($this->diagnosis);
    }

    public function submit()
    {
        $this->validate([
            'date' => 'required',
            'diagnosis' => 'required',
        ]);

        DB::transaction(function () {
            Diagnosis::where('registration_id', $this->data->id)->delete();
            Diagnosis::insert(collect($this->diagnosis)->map(fn($q, $key) => [
                'registration_id' => $this->data->id,
                'icd10_id' => $q['icd10'],
                'date' => $this->date,
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ])->toArray());

            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pelayanan/diagnosis');
    }

    public function render()
    {
        return view('livewire.pelayanan.diagnosis.form');
    }
}
