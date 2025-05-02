<?php

namespace App\Livewire\Datamaster\Tariftindakan;

use Livewire\Component;
use App\Models\ActionRate;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] 
    public $search, $exist = 1;


    public function delete($id)
    {
        ActionRate::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        ActionRate::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        ActionRate::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.tariftindakan.index', [
            'data' => ActionRate::where(fn($q) => $q->where('nama', 'like', '%' . $this->search . '%')->orWhere('description', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())->with('user')
                ->orderBy('nama')->paginate(10)
        ]);
    }
}
