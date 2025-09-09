<?php

namespace App\Livewire\Klinik\Registrasi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pasien;
use App\Models\Nakes;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;
use App\Helpers\SatusehatClass;

class Index extends Component
{
    use WithPagination;
    public $previous, $practitionerData = [], $purchase, $patient, $patientData = [];
    public $date, $patient_id, $rm, $keterangan, $practitioner_id, $nik, $name, $address, $gender, $birth_place, $birth_date, $phone, $patient_description;

    public function mount()
    {
        $this->patientData = Pasien::orderBy('name')->limit(10)->get()->toArray();
        $this->previous = url()->previous();
        $this->date = $this->date ?: date('Y-m-d');
        $this->practitionerData = Nakes::doctor()->with('employee')->orderBy('name')->get()->map(fn($q) => [
            'id' => $q->id,
            'name' => $q->name ?: $q->employee->name,
            'doctor' => $q->dokter == 1 ? 'Dokter' : '',
        ])->toArray();
    }

    public function updatedPatientId($id)
    {
        $this->patient_id = $id;
        $this->patient = Pasien::find($id);
        $this->rm = $this->patient->rm;
        $this->nik = $this->patient->nik;
        $this->name = $this->patient->name;
        $this->address = $this->patient->address;
        $this->gender = $this->patient->gender;
        $this->birth_place = $this->patient->birth_place;
        $this->birth_date = $this->patient->birth_date;
        $this->phone = $this->patient->phone;
        $this->patient_description = $this->patient->keterangan;
    }

    public function resetPatient()
    {
        $this->reset(['nik', 'rm', 'name', 'address', 'gender', 'birth_place', 'birth_date', 'phone', 'keterangan', 'patient_id']);
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
                'nik' => 'required|unique:pasiens,nik',
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
                $patient = new Pasien();
                $last = Pasien::where('created_at', 'like', date('Y-m') . '%')->withTrashed()->orderBy('created_at', 'desc')->first();
                $patient->rm = date('y.m.') . ($last ? sprintf('%04s', substr($last->rm, 6, 4) + 1) : '0001');
                $patient->user_id = auth()->id();
            } else {
                $patient = Pasien::find($this->patient_id);
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
            $data->keterangan = $this->keterangan;
            $data->practitioner_id = $this->practitioner_id;
            $data->patient_id = $this->patient_id ? $this->patient_id : $patient->id;
            $data->user_id = auth()->id();
            $data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/klinik/registrasi');
    }
    
    public function render()
    {
        return view('livewire.klinik.registrasi.index');
    }
}
