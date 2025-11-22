<?php

namespace App\Livewire\Kepegawaian\Penggajian;

use App\Models\Jurnal;
use App\Models\Pegawai;
use Livewire\Component;
use App\Models\KodeAkun;
use App\Class\JurnalClass;
use App\Models\Penggajian;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Traits\CustomValidationTrait;

class Form extends Component
{
    use CustomValidationTrait;
    public $dataPegawai = [], $unsurGaji = [], $dataKodeAkun = [], $metode_bayar;
    public $tanggal, $periode, $detail = [];

    public function mount()
    {
        $this->dataKodeAkun = KodeAkun::detail()->whereIn('parent_id', ['11100', '20000'])->get()->toArray();
        $this->tanggal = date('Y-m-01');
        $this->periode = date('Y-m');
        if (!Penggajian::where('periode', $this->periode . '-01')->exists()) {
            $this->detail = Pegawai::with('pegawaiUnsurGaji.unsurGajiKodeAkun')->orderBy('nama')->aktif()->get()->toArray();
        }
    }

    public function updatedPeriode($value)
    {
        $this->detail = [];
        if (!Penggajian::where('periode', $value . '-01')->exists()) {
            $this->detail = Pegawai::with('pegawaiUnsurGaji.unsurGajiKodeAkun')->orderBy('nama')->aktif()->get()->toArray();
        }
    }

    public function submit()
    {
        $this->validateWithCustomMessages([
            'periode' => 'required',
            'tanggal' => 'required',
        ]);

        DB::transaction(function () {
            $detail = collect($this->detail)->map(fn($q) => [
                'pegawai_id' => $q['id'],
                'nama' => $q['nama'],
                'unsur_gaji' => collect($q['pegawai_unsur_gaji'])->map(fn($q) => [
                    'nilai' => $q['nilai'],
                    'nama' => $q['unsur_gaji_nama'],
                    'sifat' => $q['unsur_gaji_sifat'],
                    'kode_akun_id' => $q['unsur_gaji_kode_akun_id'],
                ])->toArray(),
            ]);
            $penggajian = new Penggajian();
            $penggajian->tanggal = $this->tanggal;
            $penggajian->periode = $this->periode . '-01';
            $penggajian->detail = $detail->toArray();
            $penggajian->save();

            $jurnalDetail = $detail->pluck('unsur_gaji')->flatten(1)->groupBy('kode_akun_id')->map(fn($q) => [
                'debet' => $q->sum('nilai'),
                'kredit' => 0,
                'kode_akun_id' => $q->first()['kode_akun_id'],
            ])->toArray();
            $jurnalDetail[] = [
                'debet' => 0,
                'kredit' => $detail->pluck('unsur_gaji')->flatten(1)->sum('nilai'),
                'kode_akun_id' => $this->metode_bayar,
            ];
            JurnalClass::insert(
                jenis: 'Gaji',
                tanggal: $this->tanggal,
                uraian: 'Gaji Bulan ' . $this->periode,
                system: 1,
                penggajian_id: $penggajian->id,
                aset_id: null,
                pembelian_id: null,
                stok_masuk_id: null,
                pembayaran_id: null,
                detail: collect($jurnalDetail)->values()->toArray()
            );

            session()->flash('success', 'Berhasil menyimpan data');
        });
        return redirect()->to('kepegawaian/penggajian');
    }

    public function render()
    {
        return view('livewire.kepegawaian.penggajian.form');
    }
}
