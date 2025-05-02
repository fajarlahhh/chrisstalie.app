<?php

namespace App\Livewire\Datamaster\Pasien;

use App\Models\Patient;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] 
    public $search, $exist = 1;


    public function delete($id)
    {
        Patient::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        Patient::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        Patient::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.pasien.index', [
            'data' => Patient::where(fn($q) => $q->where('nama', 'like', '%' . $this->search . '%')->orWhere('rm', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())->with('user')
                ->orderBy('nama')->paginate(10)
        ]);
    }
}
