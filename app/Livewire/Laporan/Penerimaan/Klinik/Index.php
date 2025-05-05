<?php

namespace App\Livewire\Laporan\Penerimaan\Klinik;

use App\Models\Kasir;
use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\KasirPelayananTindakan;

class Index extends Component
{
    #[Url]
    public $date1, $date2, $type = "Rekap";

    public function mount()
    {
        $this->date1 = $this->date1 ?: date('Y-m-01');
        $this->date2 = $this->date2 ?: date('Y-m-d');
        $this->type = $this->type ?: "Rekap";
    }

    public function getDetail()
    {
        return KasirPelayananTindakan::with(['tarif', 'kasir.pendaftaran'])->whereHas('kasir', fn($r) => $r->whereBetween('date', [$this->date1, $this->date2]))->get();
    }

    public function getRekap()
    {
        return KasirPelayananTindakan::with('tarif')->whereHas('kasir', fn($r) => $r->whereBetween('date', [$this->date1, $this->date2]))->get()->map(function ($q) {
            return [
                'id' => $q->action_rate_id,
                'nama' => $q->tarif->nama,
                'harga' => $q->harga * $q->qty,
                'hargaAfterDiscount' => ($q->harga - ($q->harga * $q->discount / 100)) * $q->qty,
                'qty' => $q->qty,
            ];
        })->toArray();
    }

    public function print()
    {
        $cetak = view('livewire.laporan.penerimaan.klinik.cetak', [
            'cetak' => true,
            'type' => $this->type,
            'date1' => $this->date1,
            'date2' => $this->date2,
            'data' => $this->type == "Rekap" ? $this->getRekap() : $this->getDetail(),
            'admin'=> (Kasir::whereBetween('date', [$this->date1, $this->date2])->get()),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function render()
    {
        return view('livewire.laporan.penerimaan.klinik.index', [
            'data' => $this->type == "Rekap" ? $this->getRekap() : ($this->getDetail()),
            'admin'=> (Kasir::whereBetween('date', [$this->date1, $this->date2])->get()),
        ]);
    }
}
