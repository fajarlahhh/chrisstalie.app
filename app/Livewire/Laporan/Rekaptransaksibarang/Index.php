<?php

namespace App\Livewire\Laporan\Rekaptransaksibarang;

use App\Models\Barang;
use Livewire\Component;
use App\Models\KodeAkun;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Stok;

class Index extends Component
{
    #[Url]
    public $cari, $persediaan, $kode_akun_id, $bulan;
    public $cetak, $dataKodeAkun = [];

    public function mount()
    {
        $this->dataKodeAkun = KodeAkun::detail()->where('parent_id', '11300')->get()->toArray();
        $this->bulan = $this->bulan ?: date('Y-m');
    }

    private function getData()
    {
        return Barang::with(['barangSatuanUtama', 'kodeAkun'])
            ->with(
                ['stokAwal' => fn($q) => $q->where('tanggal', 'like', $this->bulan . '%')],
                ['stokMasuk' => fn($q) => $q->where('tanggal', 'like', $this->bulan . '%')],
                ['stokKeluar' => fn($q) => $q->where('tanggal', 'like', $this->bulan . '%')]
            )
            ->get();
    }

    public function render()
    {
        return view('livewire.laporan.rekaptransaksibarang.index', [
            'data' => $this->getData(),
        ]);
    }
}
