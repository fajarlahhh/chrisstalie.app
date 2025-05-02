<?php

namespace App\Livewire\Datamaster\Pegawai;

use Livewire\Component;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous;
    public $nama, $address, $phone_number, $start_date, $birth_date, $gender, $nik, $npwp, $bpjs_health, $wages, $allowance, $transport_allowance, $bpjs_health_cost, $office, $position;

    public function submit()
    {
        $this->validate([
            'nama' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
            'gender' => 'required',
            'birth_date' => 'required|date',
            'start_date' => 'required|date',
            'nik' => 'required|numeric|digits:16',
            'bpjs_health' => 'required',
            'wages' => 'required|numeric',
            'allowance' => 'required|numeric',
            'transport_allowance' => 'required|numeric',
            'bpjs_health_cost' => 'required|numeric',
            'office' => 'required',
        ]);
        DB::transaction(function () {
            $this->data->nama = $this->nama;
            $this->data->address = $this->address;
            $this->data->phone_number = $this->phone_number;
            $this->data->start_date = $this->start_date;
            $this->data->gender = $this->gender;
            $this->data->nik = $this->nik;
            $this->data->npwp = $this->npwp;
            $this->data->bpjs_health = $this->bpjs_health;
            $this->data->wages = $this->wages;
            $this->data->allowance = $this->allowance;
            $this->data->transport_allowance = $this->transport_allowance;
            $this->data->bpjs_health_cost = $this->bpjs_health_cost;
            $this->data->office = $this->office;
            $this->data->position = $this->position;
            $this->data->user_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(Employee $data)
    {
        $this->previous = url()->previous();
        $this->data = $data;
        $this->fill($this->data->toArray());
    }

    public function render()
    {
        return view('livewire.datamaster.pegawai.form');
    }
}
