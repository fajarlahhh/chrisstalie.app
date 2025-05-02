<?php

namespace App\Livewire\Datamaster\Icd10;

use Livewire\Component;
use App\Models\Icd10;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous;
    public $description, $code;

    public function submit()
    {
        $this->validate([
            'description' => 'required',
        ]);

        DB::transaction(function () {
            $this->data->description = $this->description;
            $this->data->id = $this->code;
            $this->data->user_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(Icd10 $data)
    {
        $this->previous = url()->previous();
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->code = $this->data->id;
    }

    public function render()
    {
        return view('livewire.datamaster.icd10.form');
    }
}
