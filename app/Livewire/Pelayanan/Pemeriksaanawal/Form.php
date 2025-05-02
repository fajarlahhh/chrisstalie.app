<?php

namespace App\Livewire\Pelayanan\Pemeriksaanawal;

use Livewire\Component;
use App\Models\Registration;
use App\Models\InitialExamination;
use App\Models\PhysicalExamination;
use App\Models\Ttv;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $date, $data, $complaint, $snomedCode, $anamnesis, $physicalExamination = [], $ttv = [];

    public function mount(Registration $data)
    {
        $this->date = $this->date ?: date('Y-m-d');
        $this->data = $data;
        $this->physicalExamination = [
            'Kepala' => 'Normal',
            'Mata' => 'Normal',
            'Telinga' => 'Normal',
            'Hidung' => 'Normal',
            'Mulut' => 'Normal',
            'Leher' => 'Normal',
            'Thorax' => 'Normal',
            'Paru' => 'Normal',
            'Jantung' => 'Normal',
            'Abdomen' => 'Normal',
            'Inguinal' => 'Normal',
            'Extrimitas Atas' => 'Normal',
            'Extrimitas Bawah' => 'Normal',
            'Kulit' => 'Normal',
            'Genital' => 'Normal',
            'Lain-lain' => 'Normal'
        ];
        $this->ttv = [
            'Berat Badan' => '',
            'Sistole' => '120',
            'Diastole' => '80',
            'Kesadaran' => '01',
        ];
        if ($data->initialExamination) {
            
            $this->fill($this->data->initialExamination->toArray());
            foreach ($this->data->initialExamination->physicalExamination as $key => $row) {
                $this->physicalExamination[$row->key] = $row->value;
            }
            foreach ($this->data->initialExamination->ttv as $key => $row) {
                $this->ttv[$row->key] = $row->value;
            }
        }
    }

    public function submit()
    {
        $this->validate([
            'date' => 'required',
            'complaint' => 'required',
        ]);

        DB::transaction(function () {
            InitialExamination::where('registration_id', $this->data->id)->delete();

            $initialExamination = new InitialExamination();
            $initialExamination->registration_id = $this->data->id;
            $initialExamination->date = $this->date;
            $initialExamination->complaint = $this->complaint;
            $initialExamination->snomed_code = $this->snomedCode;
            $initialExamination->user_id = auth()->id();
            $initialExamination->save();

            PhysicalExamination::insert(collect($this->physicalExamination)->map(fn($q, $key) => [
                'initial_examination_id' => $initialExamination->id,
                'key' => $key,
                'value' => $q
            ])->toArray());

            Ttv::insert(collect($this->ttv)->map(fn($q, $key) => [
                'initial_examination_id' => $initialExamination->id,
                'key' => $key,
                'value' => $q
            ])->toArray());

            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pelayanan/pemeriksaanawal');
    }


    public function render()
    {
        return view('livewire.pelayanan.pemeriksaanawal.form');
    }
}
