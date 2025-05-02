<?php

namespace App\Livewire\Gaji;

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

    public function print($id)
    {
        $data = Expenditure::findOrFail($id);
        $cetak = view('livewire.gaji.cetak', [
            'cetak' => true,
            'data' => $data,
        ])->render();
        session()->flash('cetak', $cetak);

    }

    public function delete($id)
    {
        Expenditure::findOrFail($id)->forceDelete();
    }

    public function permanentDelete($id)
    {
        Expenditure::findOrFail($id)->forceDelete();
    }
    
    public function render()
    {
        return view('livewire.gaji.index', [
            'data' => Expenditure::with('user')->where('type', 'Gaji')->with('expenditureDetail')->where('date', 'like', $this->year . '-' . $this->month . '%')->where('description', 'like', '%' . $this->search . '%')->with('employee')
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())
                ->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(10)
        ]);
    }
}
