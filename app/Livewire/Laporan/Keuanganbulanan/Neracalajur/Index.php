<?php

namespace App\Livewire\Laporan\Keuanganbulanan\Neracalajur;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\KodeAkun;

class Index extends Component
{
    #[Url]
    public $bulan;

    public function mount()
    {
        $this->bulan = date('Y-m');
    }

    public function getData()
    {
        return KodeAkun::with('kodeAkunNeraca')->get();
    }

    public function render()
    {
        return view('livewire.laporan.keuanganbulanan.neracalajur.index');
    }
}
