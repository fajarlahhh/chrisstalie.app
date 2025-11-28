<?php

namespace App\Livewire\Laporan\Kepegawaian\Jadwalshift;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Pegawai;
use App\Models\Absensi;

class Index extends Component
{
    #[Url]
    public $bulan, $dataPegawai = [], $dataAbsensi = [];

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
    }

    public function print()
    {
        $cetak = view('livewire.laporan.kepegawaian.jadwalshift.cetak', [
            'cetak' => true,
            'bulan' => $this->bulan,
            'data' => $this->getData(),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    private function getData()
    {
        return Absensi::with('pegawai')->where('tanggal', 'like', $this->bulan . '%')->get();
    }

    public function render()
    {
        return view('livewire.laporan.kepegawaian.jadwalshift.index', [
            'data' => $this->getData(),
        ]);
    }
}
