<?php

namespace App\Livewire\Laporan\Labarugi;

use App\Models\Sale;
use App\Models\Kasir;
use Livewire\Component;
use App\Models\Expenditure;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $month;

    public function mount()
    {
        $this->month = date('Y-m');
    }

    public function render()
    {
        return view('livewire.laporan.labarugi.index', [
            'penerimaan_klinik' => Kasir::where('date', 'like', $this->month . '%')->get()->sum(fn($q) => $q->amount + $q->admin),
            'penerimaan_apotek' => Sale::where('date', 'like', $this->month . '%')->get()->sum(fn($q) => $q->amount + $q->power_fee + $q->receipt_fee),
            'gaji_pegawai' => (Expenditure::where('type', 'gaji')->where('date', 'like', $this->month . '%')->get()),
            'pengeluaran_klinik' => Expenditure::select(DB::raw('sum(cost) cost'), 'expenditure_type')->where('type', 'form')->where('office', 'Klinik')->where('date', 'like', $this->month . '%')->groupBy('expenditure_type')->get(),
            'pengeluaran_apotek' => Expenditure::select(DB::raw('sum(cost) cost'), 'expenditure_type')->where('type', 'form')->where('office', 'Apotek')->where('date', 'like', $this->month . '%')->groupBy('expenditure_type')->get()
        ]);
    }
}
