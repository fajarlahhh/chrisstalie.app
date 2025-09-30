<?php

namespace App\Livewire\Klinik\Penugasan;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Registrasi;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $tanggal, $status = 1;

    public function mount()
    {
        $this->tanggal = $this->tanggal ?: date('Y-m-d');
    }

    public function render()
    {
        return view('livewire.klinik.penugasan.index', [
            'data' => Registrasi::with('pasien')->with('nakes')->with('pengguna')
                ->whereHas('tindakan')
                ->when($this->status == 1, fn($q) => $q->whereHas('tindakanBelumPenugasan', fn($q) => $q->where('tanggal_penugasan', 'like', $this->tanggal . '%')))
                ->when($this->status == 2, fn($q) => $q->whereDoesntHave('tindakanBelumPenugasan'))
                ->whereHas('pasien', fn($q) => $q->where('nama', 'like', '%' . $this->cari . '%'))
                ->orderBy('urutan', 'asc')->paginate(10)
        ]);
    }
}
