<?php

namespace App\Livewire\Datamaster\Nakes;

use Livewire\Component;
use App\Models\Nakes;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] 
    public $search, $exist = 1;


    public function delete($id)
    {
        Nakes::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        Nakes::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        Nakes::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.nakes.index', [
            'data' => Nakes::where(fn($q) => $q->where('nama', 'like', '%' . $this->search . '%')->orWhere('deskripsi', 'like', '%' . $this->search . '%'))
                ->orWhereHas('pegawai', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())->with('pengguna')
                ->orderBy('nama')->paginate(10)
        ]);
    }
}
