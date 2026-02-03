<?php

namespace App\Livewire\Datamaster\Pegawai;

use Livewire\Component;
use App\Models\KepegawaianPegawai;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $status = 'Aktif';

    public function delete($id)
    {
        KepegawaianPegawai::findOrFail($id)
            ->forceDelete();
    }

    public function restore($id)
    {
        KepegawaianPegawai::withTrashed()
            ->findOrFail($id)
            ->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.pegawai.index', [
            'data' => KepegawaianPegawai::where(fn($q) => $q
                ->where('nama', 'like', '%' . $this->cari . '%')
                ->where('status', $this->status))
                ->with('pengguna.kepegawaianPegawai')
                ->orderBy('nama')
                ->paginate(10)
        ]);
    }
}
