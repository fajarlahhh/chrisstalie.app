<?php

namespace App\Livewire\Pelayanan\Catatanpasien;

use Livewire\Component;
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
        Registration::where('registration_id', $id)->update(['note' => null]);
    }

    public function render()
    {
        return view('livewire.pelayanan.catatanpasien.index', [
            'data' => Registration::with('patient')->with('practitioner')->with('user')
                ->whereHas('patient', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))->whereDoesntHave('payment')
                ->orderBy('created_at', 'desc')->paginate(10)
        ]);
    }
}
