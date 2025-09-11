<?php

namespace App\Livewire\Klinik\Tug;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Registrasi;
use App\Models\Tug;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $tanggal, $status = '1';
    
    public function mount()
    {
        $this->tanggal = $this->tanggal ?: date('Y-m-d');
    }

    public function delete($id)
    {
        Tug::where('id', $id)->delete();
    }

    public function render()
    {
        return view('livewire.klinik.tug.index', [
            'data' => Registrasi::with('pasien')->with('nakes')->with('pengguna')
                ->when($this->status == '2', fn($q) => $q->whereHas('tug', fn($q) => $q->where('created_at', 'like', $this->tanggal . '%')))
                ->when($this->status == '1', fn($q) => $q->whereDoesntHave('tug'))
                ->whereHas('pasien', fn($q) => $q->where('nama', 'like', '%' . $this->cari . '%'))
                ->orderBy('urutan', 'asc')->paginate(10)
        ]);
    }
}
