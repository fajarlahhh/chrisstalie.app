<?php

namespace App\Livewire\Datamaster\Barang;

use App\Models\Goods;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] 
    public $search, $exist = 1, $type, $consignment = 1;


    public function delete($id)
    {
        Goods::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        Goods::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        Goods::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.barang.index', [
            'data' => Goods::with('consignment')->with('user')
                ->when($this->consignment == 2, fn($q) => $q->whereNotNull('consignment_id'))
                ->when($this->type, fn($q) => $q->where('type', $this->type))
                ->where(fn($q) => $q->where('nama', 'like', '%' . $this->search . '%')->orWhere('description', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())
                ->orderBy('nama')->paginate(10)
        ]);
    }
}
