<?php

namespace App\Livewire\Datamaster\Barang;

use App\Models\Barang;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] 
    public $search, $exist = 1, $type, $konsinyasi = 1;


    public function delete($id)
    {
        Barang::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        Barang::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        Barang::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.barang.index', [
            'data' => Barang::with('konsinyasi')->with('pengguna')
                ->when($this->konsinyasi == 2, fn($q) => $q->whereNotNull('consignment_id'))
                ->when($this->type, fn($q) => $q->where('type', $this->type))
                ->where(fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())
                ->orderBy('nama')->paginate(10)
        ]);
    }
}
