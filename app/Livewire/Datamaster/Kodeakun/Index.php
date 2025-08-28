<?php

namespace App\Livewire\Datamaster\Kodeakun;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\KodeAkun;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search;

    public function delete($id)
    {
        try {
            KodeAkun::findOrFail($id)
                ->forceDelete();
            session()->flash('success', 'Berhasil menghapus data');
        } catch (\Throwable $th) {
            session()->flash('error', 'Gagal menghapus data');
        };
    }

    public function render()
    {
        return view('livewire.datamaster.kodeakun.index', [
            'data' => KodeAkun::where(fn($q) => $q
                ->where('id', 'like', '%' . $this->search . '%')
                ->orWhere('nama', 'like', '%' . $this->search . '%'))
                ->orderBy('id')
                ->paginate(10)
        ]);
    }
}
