<?php

namespace App\Livewire\Datamaster\Icd10;

use Livewire\Component;
use App\Models\Icd10;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search, $exist = 1;


    public function delete($id)
    {
        Icd10::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        Icd10::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        Icd10::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.icd10.index', [
            'data' => Icd10::where(fn($q) => $q->where('uraian', 'like', '%' . $this->search . '%')->orWhere('id', 'like', '%' . $this->search . '%'))->with('pengguna')
                ->orderBy('uraian')->paginate(10)
        ]);
    }
}
