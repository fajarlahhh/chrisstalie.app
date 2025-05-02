<?php

namespace App\Livewire\Penjualan\Data;

use App\Models\Sale;
use Livewire\Component;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $date, $search;

    public function mount()
    {
        $this->date = $this->date ?: date('Y-m-d');
    }

    public function print($id)
    {
        $data = Sale::findOrFail($id);
        $cetak = view('livewire.penjualan.cetak', [
            'cetak' => true,
            'data' => $data,
        ])->render();
        session()->flash('cetak', $cetak);

    }

    public function delete($id)
    {
        Sale::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.penjualan.data.index', [
            'data' => Sale::with(['patient', 'saleDetail'])->where('date', $this->date)->when($this->search, fn($r) => $r->whereHas('patient', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%')))->orderBy('created_at', 'desc')->orderBy('id', 'desc')->get()
        ]);
    }
}
