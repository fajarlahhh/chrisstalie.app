<?php

namespace App\Livewire\Manajemenstok\Pengadaankonsinyasi;

use Livewire\Component;
use App\Models\Purchase;
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
        $data = Purchase::findOrFail($this->key);
        if ($data->stockExists->count() == $data->qty || $data->incomingStock->count() == 0) {
            $data->delete();
        }
    }

    public function render()
    {
        return view('livewire.manajemenstok.pengadaankonsinyasi.index', [
            'data' => Purchase::consignment()->with('purchaseDetail.goods')->with('user')->with('incomingStock')->where('date', 'like', $this->year . '-' . $this->month . '%')->where(fn($q) => $q->where('description', 'like', '%' . $this->search . '%'))
                ->when($this->exist == '2', fn($q) => $q->onlyTrashed())
                ->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(10)
        ]);
    }
}
