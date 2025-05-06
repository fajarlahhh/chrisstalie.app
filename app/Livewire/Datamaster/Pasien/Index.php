<?php

namespace App\Livewire\Datamaster\Pasien;

use App\Models\Pasien;
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
        Pasien::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        Pasien::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        Pasien::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.pasien.index', [
            'data' => Pasien::where(
                fn($q) => $q
                    ->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('rm', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%')
            )
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())->with('pengguna')
                ->orderBy('nama')->paginate(10)
        ]);
    }
}
