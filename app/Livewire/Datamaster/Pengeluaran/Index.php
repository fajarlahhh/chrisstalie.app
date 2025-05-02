<?php

namespace App\Livewire\Datamaster\Pengeluaran;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\MonthlyExpense;

class Index extends Component
{
    use WithPagination;

    #[Url] 
    public $search, $exist = 1, $type = 1;


    public function delete($id)
    {
        MonthlyExpense::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        MonthlyExpense::findOrFail($id)->forceDelete();
    }

    public function restore($id)
    {
        MonthlyExpense::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        return view('livewire.datamaster.pengeluaran.index', [
            'data' => MonthlyExpense::where(fn($q) => $q->where('name', 'like', '%' . $this->search . '%')->orWhere('description', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())->with('user')
                ->orderBy('name')->paginate(10)
        ]);
    }
}
