<?php

namespace App\Livewire\Pelayanan\Pemeriksaanawal;

use Livewire\Component;
use App\Models\Registration;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\InitialExamination;

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
        InitialExamination::where('registration_id', $id)->whereDoesntHave('registration.payment')->delete();
    }

    public function render()
    {
        return view('livewire.pelayanan.pemeriksaanawal.index', [
            'data' => Registration::with('patient')->with('practitioner')->with('user')
                ->when($this->status == '2', fn($q) => $q->whereHas('initialExamination', fn($q) => $q->where('date', $this->date)))
                ->when($this->status == '1', fn($q) => $q->whereDoesntHave('initialExamination'))
                ->whereHas('patient', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->orderBy('created_at', 'desc')->paginate(10)
        ]);
    }
}
