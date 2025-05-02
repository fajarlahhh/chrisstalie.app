<?php

namespace App\Livewire\Pelayanan\Diagnosis;

use Livewire\Component;
use App\Models\Diagnosis;
use App\Models\Registration;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    #[Url]
    public $search, $date, $status = '1';

    public function mount()
    {
        $this->date = $this->date ?: date('Y-m-d');
    }

    public function delete($id)
    {
        Diagnosis::where('registration_id', $id)->delete();
    }

    public function render()
    {
        return view('livewire.pelayanan.diagnosis.index', [
            'data' => Registration::with('patient')->with('practitioner')->with('user')
                ->when($this->status == '2', fn($q) => $q->whereHas('diagnosis', fn($q) => $q->where('date', $this->date)))
                ->when($this->status == '1', fn($q) => $q->whereDoesntHave('diagnosis'))
                ->whereHas('patient', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->whereHas('initialExamination')
                ->orderBy('created_at', 'desc')->paginate(10)
        ]);
    }
}
