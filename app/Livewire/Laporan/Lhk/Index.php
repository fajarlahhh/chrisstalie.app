<?php

namespace App\Livewire\Laporan\Lhk;

use App\Models\Sale;
use App\Models\Kasir;
use App\Models\Jurnal;
use Livewire\Component;
use App\Models\Pengguna;
use App\Models\Expenditure;
use App\Models\JurnalDetail;
use App\Models\KodeAkun;
use App\Models\MetodeBayar;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $tanggal, $pengguna_id;

    public $dataKodeAkun = [], $dataMetodeBayar = [];

    public function mount()
    {
        $this->tanggal = $this->tanggal ?: date('Y-m-d');
        $this->dataKodeAkun = KodeAkun::all()->toArray();
        $this->dataMetodeBayar = MetodeBayar::all()->toArray();
    }

    public function getData()
    {
        return Jurnal::with(['pengguna'])
            ->select('jurnal.*', 'jurnal_detail.kode_akun_id as kode_akun_id', 'jurnal_detail.debet as debet', 'jurnal_detail.kredit as kredit', 'pembayaran.metode_bayar as metode_bayar')
            ->rightJoin('jurnal_detail', 'jurnal.id', '=', 'jurnal_detail.jurnal_id')
            ->leftJoin('pembayaran', 'jurnal.pembayaran_id', '=', 'pembayaran.id')
            ->whereIn('jurnal.id', JurnalDetail::whereIn('kode_akun_id', (collect($this->dataKodeAkun)->where('parent_id', '11100')->pluck('id')))->pluck('jurnal_id'))
            ->when($this->pengguna_id, fn($q) => $q->where('pengguna_id', $this->pengguna_id))
            ->where('tanggal', $this->tanggal)->get();
    }

    public function render()
    {
        return view('livewire.laporan.lhk.index', [
            'data' =>  $this->getData(),
            'dataPengguna' => Pengguna::whereIn('id', Jurnal::where('tanggal', $this->tanggal)->pluck('pengguna_id'))->get()
        ]);
    }
}
