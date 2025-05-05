<?php

namespace App\Livewire\Laporan\Jasapelayanan;

use App\Exports\PembagianjasapelayananExport;
use Livewire\Component;
use App\Models\Treatment;
use App\Models\Tarif;
use App\Models\Nakes;
use Livewire\Attributes\Url;
use App\Models\PaymentTreatment;

class Index extends Component
{
    #[Url]
    public $date1, $date2;

    public function mount()
    {
        $this->date1 = $this->date1 ?: date('Y-m-01');
        $this->date2 = $this->date2 ?: date('Y-m-d');
    }

    public function export()
    {
        return (new PembagianjasapelayananExport($this->date1, $this->date2))->download('pembagianjasapelayanan' . $this->date1 . '-' . $this->date2 . '.xls');
    }

    public function getNakes()
    {
        return (Nakes::with('pegawai')->withTrashed()->whereIn('id', PaymentTreatment::whereHas('payment', fn($q) => $q->whereBetween('date', [$this->date1, $this->date2]))->get()->map(function ($item) {
            return [
                'nakes_id' => $item->nakes_id,
                'beautician_id' => $item->beautician_id,
            ];
        })->flatten()->unique()->filter(function ($value) {
            return $value !== null;
        })->toArray())->get()->map(fn($q) => [
            'id' => $q->id,
            'nama' => $q->pegawai ? $q->pegawai->nama : $q->nama,
        ]));
    }

    public function getData()
    {
        return (PaymentTreatment::with('actionRate')->whereHas('payment', fn($r) => $r->whereBetween('date', [$this->date1, $this->date2]))->get()->map(function ($q) {
            $harga = $q->harga * $q->qty;
            $keuntungan = $q->keuntungan * $q->qty;
            $modal = $q->modal * $q->qty;
            $beauticianFee = $q->upah_petugas * $q->qty;
            $dicount = ($q->harga * $q->discount / 100) * $q->qty;
            $profitAfterDicount = $keuntungan - $dicount;
            $nakesPortion = ($profitAfterDicount - $beauticianFee) * $q->porsi_nakes;
            return [
                'id' => $q->action_rate_id,
                'nama' => $q->actionRate->nama,
                'harga' => $harga,
                'discount_percent' => $q->discount,
                'qty' => $q->qty,
                'modal' => $modal,
                'discount' => $dicount,
                'keuntungan' => $profitAfterDicount,
                'upah_petugas' => $beauticianFee,
                'porsi_nakes' => $q->nakes_id ? $nakesPortion : 0,
                'porsi_kantor' => ($profitAfterDicount - $beauticianFee) * $q->porsi_kantor
                    + ($q->nakes_id ? 0 : $nakesPortion)
                    + ($q->beautician_id ? 0 : $beauticianFee),
                'beautician_id' => $q->beautician_id,
                'nakes_id' => $q->nakes_id,
            ];
        })->toArray());
    }

    public function render()
    {
        return view('livewire.laporan.jasapelayanan.index', [
            'data' => ($this->getData()),
            'nakes' => ($this->getNakes()),
        ]);
    }
}
