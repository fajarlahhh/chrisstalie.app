<?php

namespace App\Livewire\Pelayanan\PelayananDiagnosa;

use App\Models\PelayananDiagnosa;
use App\Models\Icd10;
use Livewire\Component;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $date, $data, $dataIcd10 = [], $pelayananDiagnosa = [];

    public function mount(Pendaftaran $data)
    {
        $this->date = $this->date ?: date('Y-m-d');
        $this->data = $data;
        $this->dataIcd10 = Icd10::orderBy('uraian')->get()->toArray();
        $this->pelayananDiagnosa = $data->pelayananDiagnosa->map(fn($q) => ['icd10' => $q->icd10_id])->toArray();
    }

    public function addPelayananDiagnosa()
    {
        $this->pelayananDiagnosa[] = ['icd10' => null];
    }

    public function deletePelayananDiagnosa($index)
    {
        unset($this->pelayananDiagnosa[$index]);
        $this->pelayananDiagnosa = array_merge($this->pelayananDiagnosa);
    }

    public function submit()
    {
        $this->validate([
            'date' => 'required',
            'pelayananDiagnosa' => 'required',
        ]);

        DB::transaction(function () {
            PelayananDiagnosa::where('pendaftaran_id', $this->data->id)->delete();
            PelayananDiagnosa::insert(collect($this->pelayananDiagnosa)->map(fn($q, $key) => [
                'pendaftaran_id' => $this->data->id,
                'icd10_id' => $q['icd10'],
                'date' => $this->date,
                'pengguna_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ])->toArray());

            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pelayanan/pelayananDiagnosa');
    }

    public function render()
    {
        return view('livewire.pelayanan.pelayananDiagnosa.form');
    }
}
