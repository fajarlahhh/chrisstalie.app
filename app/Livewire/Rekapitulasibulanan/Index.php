<?php

namespace App\Livewire\Rekapitulasibulanan;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\KodeAkun;
use App\Models\KeuanganSaldo;
use App\Models\StokAwal;
use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    #[Url]
    public $bulan;

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m', strtotime('-1 month'));
    }

    public function stok($periodeSelanjutnya)
    {
        $data = Barang::with(['stokAwal' => fn($q) => $q->where('tanggal', $this->bulan . '-01')])
            ->with(['stokMasuk' => fn($q) => $q->where('tanggal', 'like',  $this->bulan . '%')])
            ->with(['stokKeluar' => fn($q) => $q->where('tanggal', 'like',  $this->bulan . '%')])
            ->get();

        StokAwal::where('tanggal', $periodeSelanjutnya->format('Y-m-01'))->delete();
        StokAwal::insert($data->map(
            fn($q) =>
            [
                'barang_id' => $q->id,
                'tanggal' =>  $periodeSelanjutnya->format('Y-m-01'),
                'qty' => $q->stokAwal->sum('qty') + $q->stokMasuk->sum(fn($q) => $q['qty'] * $q['rasio_dari_terkecil']) - $q->stokKeluar->sum(fn($q) => $q['qty'] * $q['rasio_dari_terkecil']),
                'pengguna_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        )->toArray());
    }

    public function keuangan($periode, $periodeSelanjutnya, $periodeSekarang)
    {
        $diff = $periode->diffInMonths($periodeSekarang);
        if ($diff > 12) {
            $diff = 24;
        }
        $now = now();
        for ($i = 0; $i < $diff; $i++) {
            $saldo = [];

            KeuanganSaldo::where('periode', $periodeSelanjutnya->format('Y-m-01'))->delete();

            $dataAkun = KodeAkun::with([
                'keuanganSaldo' => fn($q) => $q->selectRaw("kode_akun_id, debet, kredit")
                    ->where("periode", $periode->format('Y-m-01'))
            ])
                ->with([
                    'keuanganJurnalDetail' => fn($q) => $q->withoutGlobalScopes()->selectRaw("kode_akun_id, sum(debet) debet, sum(kredit) kredit")
                        ->whereHas(
                            'keuanganJurnal',
                            fn($r) =>
                            $r->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode->format('Y-m')])
                        )
                        ->groupBy('kode_akun_id')
                ])
                ->detail()
                ->orderBy('id', 'asc')
                ->get();

            if ($dataAkun) {
                $labaRugi = $dataAkun->filter(fn($q) => $q['kategori'] == 'Pendapatan')->sum(
                    fn($q) => ($q->keuanganJurnalDetail->sum('kredit')) - ($q->keuanganJurnalDetail->sum('debet'))
                ) - $dataAkun->filter(fn($q) => $q['kategori'] == 'Beban')->sum(
                    fn($q) => ($q->keuanganJurnalDetail->sum('debet')) - ($q->keuanganJurnalDetail->sum('kredit'))
                );

                foreach ($dataAkun as $key => $row) {
                    $debetJurnal = $row->keuanganJurnalDetail->sum('debet');
                    $kreditJurnal = $row->keuanganJurnalDetail->sum('kredit');

                    $saldoDebet = sizeof($row->keuanganSaldo) > 0 ? $row->keuanganSaldo->sum('debet') : 0;
                    $saldoKredit = sizeof($row->keuanganSaldo) > 0 ? $row->keuanganSaldo->sum('kredit') : 0;

                    $debetNeraca = 0;
                    $kreditNeraca = 0;
                    if ($row->kategori == 'Aktiva') {
                        $debetNeraca = ($saldoDebet - $saldoKredit) + ($debetJurnal - $kreditJurnal);
                    } else if ($row->kategori == 'Kewajiban' || $row->kategori == 'Ekuitas') {
                        if ($row->laba_rugi == 1) {
                            $kreditNeraca = ($saldoKredit - $saldoDebet) + ($kreditJurnal - $debetJurnal) + $labaRugi;
                        } else {
                            $kreditNeraca = ($saldoKredit - $saldoDebet) + ($kreditJurnal - $debetJurnal);
                        }
                    }

                    array_push($saldo, [
                        'id' => str_replace('.', '', $row->id) . $periodeSelanjutnya->format('Ym01') . '1',
                        'periode' => $periodeSelanjutnya->format('Y-m-01'),
                        'kode_akun_id' => $row->id,
                        'debet_jurnal' => $debetJurnal,
                        'kredit_jurnal' => $kreditJurnal,
                        'kredit' => $kreditNeraca,
                        'debet' => $debetNeraca,
                        'pengguna_id' => auth()->id(),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
            $ds = collect($saldo)->chunk(2000);
            foreach ($ds as $sal) {
                KeuanganSaldo::insert($sal->toArray());
            }

            $periode->addMonths(1);
            $periodeSelanjutnya->addMonths(1);
        }
    }

    public function penyusutan($periode) {}

    public function submit()
    {
        DB::transaction(function () {
            $periode = Carbon::parse($this->bulan . '-01');
            $periodeSekarang = Carbon::now();
            $periodeSelanjutnya = Carbon::parse($this->bulan . '-01')->addMonths(1);
            $this->stok($periodeSelanjutnya);
            $this->keuangan($periode, $periodeSelanjutnya, $periodeSekarang);
            $this->penyusutan($periode);

            session()->flash('success', 'Berhasil menyimpan data');
        });
    }

    public function render()
    {
        return view('livewire.rekapitulasibulanan.index');
    }
}
