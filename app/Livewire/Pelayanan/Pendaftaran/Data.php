<?php

namespace App\Livewire\Pelayanan\Pendaftaran;

use Livewire\Component;
use App\Models\Registration;
use Livewire\Attributes\Url;

class Data extends Component
{
    #[Url]
    public $search, $date;

    public function mount()
    {
        $this->date = $this->date ?: date('Y-m-d');
    }

    public function delete($id)
    {
        Registration::where('id', $id)->whereDoesntHave('payment')->delete();
    }

    public function render()
    {
        return view('livewire.pelayanan.pendaftaran.data', [
            'data' => Registration::with(['initialExamination', 'diagnosis', 'treatment', 'payment'])->with('pasien')->with('nakes')->with('pengguna')->whereHas('pasien', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->where('datetime', 'like', $this->date . '%')
                ->orderBy('created_at', 'desc')->paginate(10)
        ]);
    }
}
