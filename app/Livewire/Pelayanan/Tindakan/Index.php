<?php

namespace App\Livewire\Pelayanan\Tindakan;

use Livewire\Component;
use App\Models\Treatment;
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
        Treatment::where('registration_id', $id)->delete();
    }

    public function render()
    {
        return view('livewire.pelayanan.tindakan.index', [
            'data' => Registration::with('pasien')->with('nakes')->with('treatment')->with('toolMaterial')->with('pengguna')
                ->when($this->status == '2', fn($q) => $q->whereHas('treatment', fn($q) => $q->where('date', $this->date)))
                ->when($this->status == '1', fn($q) => $q->whereDoesntHave('treatment'))
                ->whereHas('pasien', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->orderBy('created_at', 'desc')->paginate(10)
        ]);
    }
}
