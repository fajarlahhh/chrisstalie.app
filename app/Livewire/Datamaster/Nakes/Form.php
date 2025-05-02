<?php

namespace App\Livewire\Datamaster\Nakes;

use App\Class\SatusehatClass;
use App\Models\Employee;
use Livewire\Component;
use App\Models\Practitioner;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $employeeData = [];
    public $nama, $ihs, $nik, $gender, $description, $address, $phone_number, $doctor = false, $employee_id;

    public function submit()
    {
        if (!$this->employee_id) {
            $this->validate([
                'nama' => 'required',
                'nik' => 'required',
                'gender' => 'required',
                'address' => 'required',
                'phone_number' => 'required',
            ]);
        }

        DB::transaction(function () {
            if ($this->employee_id) {
                $practitionerSatuSehat = SatusehatClass::getPractitionerByNik(Employee::find($this->employee_id)->nik);
            } else {
                $practitionerSatuSehat = SatusehatClass::getPractitionerByNik($this->nik);
            }
            $this->data->employee_id = $this->employee_id;
            $this->data->nama = $this->nama;
            $this->data->ihs = $this->ihs;
            $this->data->nik = $this->nik;
            $this->data->description = $this->description;
            $this->data->gender = $this->gender;
            $this->data->address = $this->address;
            $this->data->phone_number = $this->phone_number;
            $this->data->doctor = $this->doctor ? 1 : 0;
            $this->data->user_id = auth()->id();
            $this->data->ihs = $practitionerSatuSehat ? ($practitionerSatuSehat['entry'] ? $practitionerSatuSehat['entry']['0']['resource']['id'] : null)  : null;
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(Practitioner $data)
    {
        $this->previous = url()->previous();
        $this->employeeData = Employee::orderBy('nama')->with('user')->get()->toArray();
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->doctor = $this->data->doctor == 1 ? true : false;
    }

    public function render()
    {
        return view('livewire.datamaster.nakes.form');
    }
}
