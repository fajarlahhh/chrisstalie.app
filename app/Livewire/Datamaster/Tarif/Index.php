<?php

namespace App\Livewire\Datamaster\Tarif;

use Livewire\Component;
use App\Models\Tarif;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] 
    public $search, $exist = 1;


    public function delete($id)
    {
        Tarif::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        Tarif::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        Tarif::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.tarif.index', [
            'data' => Tarif::where(fn($q) => $q->where('nama', 'like', '%' . $this->search . '%')->orWhere('deskripsi', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())->with('pengguna')
                ->orderBy('nama')->paginate(10)
        ]);
    }
}
