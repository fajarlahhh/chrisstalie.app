<?php

namespace App\Livewire\Pelayanan\PelayananDiagnosa;

use Livewire\Component;
use App\Models\PelayananDiagnosa;
use App\Models\Pendaftaran;
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
        PelayananDiagnosa::where('pendaftaran_id', $id)->delete();
    }

    public function render()
    {
        return view('livewire.pelayanan.pelayananDiagnosa.index', [
            'data' => Pendaftaran::with('pasien')->with('nakes')->with('pengguna')
                ->when($this->status == '2', fn($q) => $q->whereHas('pelayananDiagnosa', fn($q) => $q->where('date', $this->date)))
                ->when($this->status == '1', fn($q) => $q->whereDoesntHave('pelayananDiagnosa'))
                ->whereHas('pasien', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->whereHas('pelayananPemeriksaanAwal')
                ->orderBy('created_at', 'desc')->paginate(10)
        ]);
    }
}
