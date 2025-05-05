<?php

namespace App\Exports;

use App\Models\Nakes;
use App\Models\KasirPelayananTindakan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class PembagianjasapelayananExport implements FromView
{
    use Exportable;
    private $date1, $date2, $kategori, $audit;

    public function __construct(string $date1, string $date2)
    {
        $this->date1 = $date1;
        $this->date2 = $date2;
    }

    public function getNakes()
    {
        return (Nakes::with('pegawai')->withTrashed()->whereIn('id', KasirPelayananTindakan::whereHas('kasir', fn($q) => $q->whereBetween('date', [$this->date1, $this->date2]))->get()->map(function ($item) {
            return [
                'nakes_id' => $item->nakes_id,
                'beautician_id' => $item->beautician_id,
            ];
        })->flatten()->unique()->filter(function ($value) {
            return $value !== null;
        })->toArray())->get()->map(fn($q) => [
            'id' => $q->id,
            'name' => $q->pegawai ? $q->pegawai->nama : $q->name,
        ]));
    }

    public function getData()
    {
        return (KasirPelayananTindakan::with('tarif')->whereHas('kasir', fn($r) => $r->whereBetween('date', [$this->date1, $this->date2]))->get()->map(function ($q) {
            $dicount = ($q->harga * $q->discount / 100) * $q->qty;
            $profitAfterDicount = $q->keuntungan - $dicount;
            $nakesPortion = ($profitAfterDicount - $q->upah_petugas) * $q->porsi_nakes;
            return [
                'id' => $q->action_rate_id,
                'name' => $q->tarif->name,
                'harga' => $q->harga,
                'discount_percent' => $q->discount,
                'qty' => $q->qty,
                'modal' => $q->modal,
                'discount' => $dicount,
                'keuntungan' => $profitAfterDicount,
                'upah_petugas' => $q->upah_petugas,
                'porsi_nakes' => $q->nakes_id ? $nakesPortion : 0,
                'porsi_kantor' => ($profitAfterDicount - $q->upah_petugas) * $q->porsi_kantor + ($q->nakes_id ? 0 : $nakesPortion) + ($q->beautician_id ? 0 : $q->upah_petugas),
                'beautician_id' => $q->beautician_id,
                'nakes_id' => $q->nakes_id,
            ];
        })->toArray());
    }

    public function view(): View
    {
        return view('livewire.laporan.jasapelayanan.cetak', [
            'cetak' => true,
            'date1' => $this->date1,
            'date2' => $this->date2,
            'data' => ($this->getData()),
            'nakes' => ($this->getNakes()),
        ]);
    }
}