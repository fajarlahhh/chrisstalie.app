<?php

namespace App\Livewire\Manajemenstok\Barangmasuk;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\IncomingStok;

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
        $data = IncomingStok::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.manajemenstok.barangmasuk.index', [
            'data' => IncomingStok::where('date', 'like', $this->year . '-' . $this->month . '%')->with('pengguna')->with(['availableStok', 'purchase'])->when($this->search, fn($q) => $q->where('uraian', 'like', '%' . $this->search . '%')->orWhereHas('goods', fn($r) => $r->where('nama', 'like', '%' . $this->search . '%')))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())
                ->orderBy('date', 'desc')->paginate(10)
        ]);
    }
}
