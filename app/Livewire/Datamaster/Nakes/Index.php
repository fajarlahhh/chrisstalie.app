<?php

namespace App\Livewire\Datamaster\Nakes;

use Livewire\Component;
use App\Models\Practitioner;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] 
    public $search, $exist = 1;


    public function delete($id)
    {
        Practitioner::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        Practitioner::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        Practitioner::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.nakes.index', [
            'data' => Practitioner::where(fn($q) => $q->where('nama', 'like', '%' . $this->search . '%')->orWhere('description', 'like', '%' . $this->search . '%'))
                ->orWhereHas('employee', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())->with('user')
                ->orderBy('nama')->paginate(10)
        ]);
    }
}
