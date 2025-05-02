<?php

namespace App\Livewire\Informasipasien;

use App\Models\Patient;
use Livewire\Component;

class Index extends Component
{
    public $patient_id, $patient;

    public function updatedPatientId($value)
    {
        $this->patient = Patient::where('id', $value)->first();
    }

    public function render()
    {
        return view('livewire.informasipasien.index');
    }
}
