<?php

namespace App\Livewire\Datamaster\Pegawai;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] 
    public $search, $exist = 1, $type = 1;


    public function delete($id)
    {
        Employee::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        Employee::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        Employee::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.pegawai.index', [
            'data' => Employee::where(fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())->with('user')
                ->orderBy('name')->paginate(10)
        ]);
    }
}
