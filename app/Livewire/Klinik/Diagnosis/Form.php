<?php

namespace App\Livewire\Klinik\Diagnosis;

use Livewire\Component;
use App\Models\Registrasi;
use App\Models\Icd10;
use Illuminate\Support\Facades\DB;
use App\Models\Diagnosis;

class Form extends Component
{
    public $data;
    public $dataIcd10 = [];
    public $diagnosis_banding;
    public $rencana_pemeriksaan;
    public $rencana_terapi;
    public $diagnosis = [];

    public function tambahDiagnosis()
    {
        $this->diagnosis[] = ['icd10' => null];
    }

    public function hapusDiagnosis($index)
    {
        unset($this->diagnosis[$index]);
        $this->diagnosis = array_merge($this->diagnosis);
    }

    public function mount(Registrasi $data)
    {
        $this->data = $data;
        if ($data->diagnosis) {
            $this->fill($data->diagnosis->toArray());
            $this->diagnosis = json_decode($data->diagnosis->icd10, true);
        }else{
            $this->diagnosis[] = ['icd10' => null];
        }
        $this->dataIcd10 = Icd10::orderBy('uraian')->get()->toArray();
    }

    public function submit()
    {
        $this->validate([
            'diagnosis' => 'required|array',
            'diagnosis.*.icd10' => 'required',
            'diagnosis_banding' => 'required',
            'rencana_terapi' => 'required',
            'rencana_pemeriksaan' => 'required',
        ]);
        DB::transaction(function () {
            
            Diagnosis::where('id', $this->data->id)->delete();
            
            $data = new Diagnosis();
            $data->id = $this->data->id;
            $data->pasien_id = $this->data->pasien_id;
            $data->pengguna_id = auth()->id();
            $data->icd10 = json_encode($this->diagnosis);
            $data->diagnosis_banding = $this->diagnosis_banding;
            $data->rencana_terapi = $this->rencana_terapi;
            $data->rencana_pemeriksaan = $this->rencana_pemeriksaan;
            $data->save();
        });
        session()->flash('success', 'Berhasil menyimpan data Diagnosis');
    }

    public function render()
    {
        return view('livewire.klinik.diagnosis.form');
    }
}
