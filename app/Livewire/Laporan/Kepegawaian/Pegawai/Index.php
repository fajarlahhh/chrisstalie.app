<?php

namespace App\Livewire\Laporan\Kepegawaian\Pegawai;

use Livewire\Component;
use App\Models\KepegawaianPegawai;

class Index extends Component
{
    public function print()
    {
        $cetak = view('livewire.laporan.kepegawaian.pegawai.cetak', [
            'cetak' => true,
            'data' => $this->getData(),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    private function getData()
    {
        return KepegawaianPegawai::all()->toArray();
    }

    public function render()
    {
        return view('livewire.laporan.kepegawaian.pegawai.index', [
            'data' => $this->getData(),
        ]);
    }
}
