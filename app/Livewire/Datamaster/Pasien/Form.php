<?php

namespace App\Livewire\Datamaster\Pasien;

use App\Models\Patient;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous;
    public $name, $ihs, $nik, $rm, $gender, $birth_place, $birth_date, $registration_date, $description, $address, $phone, $doctor = false;

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'nik' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        DB::transaction(function () {
            $this->data->name = $this->name;
            $this->data->ihs = $this->ihs;
            $this->data->nik = $this->nik;
            $this->data->description = $this->description;
            $this->data->gender = $this->gender;
            $this->data->address = $this->address;
            $this->data->phone = $this->phone;
            $this->data->doctor = $this->doctor ? 1 : 0;
            $this->data->user_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(Patient $data)
    {
        $this->previous = url()->previous();
        $this->data = $data;
        $this->fill($this->data->toArray());
    }
    
    public function render()
    {
        return view('livewire.datamaster.pasien.form');
    }
}
