<?php

namespace App\Livewire\Pelayanan\Pemeriksaanawal;

use Livewire\Component;
use App\Models\Pendaftaran;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\PelayananPemeriksaanAwal;

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
        PelayananPemeriksaanAwal::where('pendaftaran_id', $id)->whereDoesntHave('pendaftaran.kasir')->delete();
    }

    public function render()
    {
        return view('livewire.pelayanan.pemeriksaanawal.index', [
            'data' => Pendaftaran::with('pasien')->with('nakes')->with('pengguna')
                ->when($this->status == '2', fn($q) => $q->whereHas('pelayananPemeriksaanAwal', fn($q) => $q->where('date', $this->date)))
                ->when($this->status == '1', fn($q) => $q->whereDoesntHave('pelayananPemeriksaanAwal'))
                ->whereHas('pasien', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->orderBy('created_at', 'desc')->paginate(10)
        ]);
    }
}
