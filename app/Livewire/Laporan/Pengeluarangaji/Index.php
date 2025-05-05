<?php

namespace App\Livewire\Laporan\Pengeluarangaji;

use Livewire\Component;
use App\Models\Expenditure;

class Index extends Component
{
    public $date1, $date2;

    public function mount()
    {
        $this->date1 = $this->date1 ?: date('Y-m-01');
        $this->date2 = $this->date2 ?: date('Y-m-d');
    }

    public function getData()
    {
        return Expenditure::with(['pegawai', 'pengguna', 'expenditureDetail'])->where('type', 'gaji')->whereBetween('date', [$this->date1, $this->date2])->get();
    }

    public function print()
    {
        $cetak = view('livewire.laporan.pengeluarangaji.cetak', [
            'cetak' => true,
            'date1' => $this->date1,
            'date2' => $this->date2,
            'data' => $this->getData(),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function render()
    {
        return view('livewire.laporan.pengeluarangaji.index', [
            'data' => $this->getData()
        ]);
    }
}
