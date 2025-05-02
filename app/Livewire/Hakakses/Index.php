<?php

namespace App\Livewire\Hakakses;

use App\Models\User;
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
        if ($this->key != 1) {
            User::findOrFail($id)->delete();
            $this->reset(['key']);
        }
    }

    public function permanentDelete($id)
    {
        if ($this->key != 1) {
            User::findOrFail($id)->forceDelete();
            $this->reset(['key']);
        }
    }

    public function restore($id)
    {
        User::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.hakakses.index', [
            'data' => User::where('email', '!=', 'rafaskinclinic@gmail.com')->where(fn($q) => $q->where('email', 'like', '%' . $this->search . '%')->orWhere('nama', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())
                ->orderBy('nama')->paginate(10)
        ]);
    }
}
