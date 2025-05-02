<?php

namespace App\Livewire\Pengeluaran;

use Livewire\Component;
use App\Models\Expenditure;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    #[Url]
    public $search, $exist = 1, $month, $year;

    public function mount()
    {
        $this->month = $this->month ?: date('m');
        $this->year = $this->year ?: date('Y');
    }

    public function delete($id)
    {
        Expenditure::findOrFail($id)->delete();
    }

    public function permanentDelete($id)
    {
        Expenditure::findOrFail($id)->forceDelete();
    }

    public function render()
    {
        return view('livewire.pengeluaran.index', [
            'data' => Expenditure::with('user')->with('expenditureDetail')->where('date', 'like', $this->year . '-' . $this->month . '%')->where('description', 'like', '%' . $this->search . '%')
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())
                ->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(10)
        ]);
    }
}
