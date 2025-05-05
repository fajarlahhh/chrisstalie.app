<?php

namespace App\Livewire\Datamaster\Pegawai;

use Livewire\Component;
use App\Models\Pegawai;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] 
    public $search, $exist = 1, $type = 1;


    public function delete($id)
    {
        Pegawai::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        Pegawai::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        Pegawai::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.pegawai.index', [
            'data' => Pegawai::where(fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())->with('pengguna')
                ->orderBy('nama')->paginate(10)
        ]);
    }
}
