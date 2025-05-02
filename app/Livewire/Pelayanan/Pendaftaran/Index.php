<?php

namespace App\Livewire\Pelayanan\Pendaftaran;

use App\Models\Patient;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Practitioner;
use App\Models\Registration;
use Livewire\WithPagination;
use App\Class\SatusehatClass;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;
    public $previous, $practitionerData = [], $purchase, $patient, $patientData = [];
    public $date, $patient_id, $rm, $description, $practitioner_id, $nik, $name, $address, $gender, $birth_place, $birth_date, $phone, $patient_description;

    public function mount()
    {
        $this->patientData = Patient::orderBy('name')->limit(10)->get()->toArray();
        $this->previous = url()->previous();
        $this->date = $this->date ?: date('Y-m-d');
        $this->practitionerData = Practitioner::doctor()->with('employee')->orderBy('name')->get()->map(fn($q) => [
            'id' => $q->id,
            'name' => $q->name ?: $q->employee->name,
            'doctor' => $q->doctor == 1 ? 'Dokter' : '',
        ])->toArray();
    }

    public function updatedPatientId($id)
    {
        $this->patient_id = $id;
        $this->patient = Patient::find($id);
        $this->rm = $this->patient->rm;
        $this->nik = $this->patient->nik;
        $this->name = $this->patient->name;
        $this->address = $this->patient->address;
        $this->gender = $this->patient->gender;
        $this->birth_place = $this->patient->birth_place;
        $this->birth_date = $this->patient->birth_date;
        $this->phone = $this->patient->phone;
        $this->patient_description = $this->patient->patient_description;
    }

    public function resetPatient()
    {
        $this->reset(['nik', 'rm', 'name', 'address', 'gender', 'birth_place', 'birth_date', 'phone', 'patient_description', 'patient_id']);
    }

    public function submit()
    {
        if ($this->patient_id) {
            $this->validate([
                'date' => 'required',
                'address' => 'required',
                'practitioner_id' => 'required',
            ]);
        } else {
            $this->validate([
                'date' => 'required',
                'practitioner_id' => 'required',
                'nik' => 'required|unique:patients,nik',
                'name' => 'required',
                'address' => 'required',
                'gender' => 'required',
                'birth_place' => 'required',
                'birth_date' => 'required',
                'phone' => 'required',
            ]);
        }
        DB::transaction(function () {
            $patientSatuSehat = SatusehatClass::getPatientByNik($this->nik);
            if (!$this->patient_id) {
                $patient = new Patient();
                $last = Patient::where('created_at', 'like', date('Y-m') . '%')->orderBy('created_at', 'desc')->first();
                $patient->rm = date('y.m.') . ($last ? sprintf('%04s', substr($last->rm, 6, 4) + 1) : '0001');
                $patient->user_id = auth()->id();
            } else {
                $patient = Patient::find($this->patient_id);
            }
            $patient->nik = $this->nik;
            $patient->name = $this->name;
            $patient->address = $this->address;
            $patient->gender = $this->gender;
            $patient->birth_place = $this->birth_place;
            $patient->birth_date = $this->birth_date;
            $patient->phone = $this->phone;
            $patient->registration_date = $this->date;
            $patient->ihs = $patientSatuSehat ? $patientSatuSehat['id'] : null;
            $patient->save();

            $datetime = $this->date . date(' H:i:s');
            $data = new Registration();
            if (!$this->patient_id) {
                $data->new = 1;
            }
            $data->datetime = $datetime;
            $data->order = str_replace(['/', ':', '-', ' '], '', $datetime);
            $data->description = $this->description;
            $data->practitioner_id = $this->practitioner_id;
            $data->patient_id = $this->patient_id ? $this->patient_id : $patient->id;
            $data->user_id = auth()->id();
            $data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pelayanan/pendaftaran');
    }

    public function render()
    {
        return view('livewire.pelayanan.pendaftaran.index');
    }
}
