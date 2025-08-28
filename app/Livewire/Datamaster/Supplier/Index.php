<?php

namespace App\Livewire\Datamaster\Supplier;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search, $exist = 1;
    
    public function delete($id)
    {
        Supplier::findOrFail($id)
            ->forceDelete();
    }

    public function restore($id)
    {
        Supplier::withTrashed()
            ->findOrFail($id)
            ->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.supplier.index', [
            'data' => Supplier::where(fn($q) => $q
                ->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())
                ->with('pengguna')
                ->orderBy('nama')
                ->paginate(10)
        ]);
    }
}
