<?php

namespace App\Livewire\Laporan\Pengeluaran;

use App\Models\Expenditure;
use App\Models\ExpenditureDetail;
use Livewire\Component;

class Index extends Component
{
    public $date1, $date2, $office;

    public function mount()
    {
        $this->date1 = $this->date1 ?: date('Y-m-01');
        $this->date2 = $this->date2 ?: date('Y-m-d');
    }

    public function getData()
    {
        return Expenditure::with(['pegawai', 'pengguna'])->where('type', 'form')->when($this->office, fn($q) => $q->where('office', $this->office))->whereBetween('date', [$this->date1, $this->date2])->get();
    }

    public function print()
    {
        $cetak = view('livewire.laporan.pengeluaran.cetak', [
            'cetak' => true,
            'date1' => $this->date1,
            'date2' => $this->date2,
            'data' => $this->getData(),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function render()
    {
        return view('livewire.laporan.pengeluaran.index', [
            'data' => $this->getData()
        ]);
    }
}
