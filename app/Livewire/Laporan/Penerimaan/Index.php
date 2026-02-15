<?php

namespace App\Livewire\Laporan\Penerimaan;

use App\Models\Nakes;
use Livewire\Component;
use App\Models\Pengguna;
use App\Models\Tindakan;
use App\Class\BarangClass;
use App\Models\Pembayaran;
use App\Models\Registrasi;
use App\Models\MetodeBayar;
use Livewire\Attributes\Url;
use App\Class\JurnalkeuanganClass;
use App\Models\TindakanAlatBarang;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\CustomValidationTrait;
use App\Traits\KodeakuntransaksiTrait;
use App\Exports\LaporanpenerimaanExport;

class Index extends Component
{
    use CustomValidationTrait, KodeakuntransaksiTrait;
    #[Url]
    public $tanggal1, $tanggal2, $pengguna_id, $metode_bayar;
    public $dataMetodeBayar = [];

    public function mount()
    {
        $this->tanggal1 = $this->tanggal1 ?: date('Y-m-d');
        $this->tanggal2 = $this->tanggal2 ?: date('Y-m-d');
        $this->dataMetodeBayar = MetodeBayar::get()->toArray();
        $this->dataBarang = collect(BarangClass::getBarang());
        $this->dataBarangApotek = collect($this->dataBarang)->where('persediaan', 'Apotek')->toArray();
        $this->dataNakes = Nakes::with('kepegawaianPegawai')->orderBy('nama')->get()->map(fn($q) => [
            'id' => $q->id,
            'dokter' => $q->dokter,
            'perawat' => $q->perawat,
            'nama' => $q->kepegawaianPegawai ? $q->kepegawaianPegawai->nama : $q->nama,
            'kode_akun_jasa_dokter_id' => $q->kode_akun_jasa_dokter_id,
            'kode_akun_jasa_perawat_id' => $q->kode_akun_jasa_perawat_id,
        ])->toArray();
    }

    public function export()
    {
        return Excel::download(new LaporanpenerimaanExport(
            $this->getData(false),
            $this->tanggal1,
            $this->tanggal2,
            Pengguna::find($this->pengguna_id)?->nama,
            $this->metode_bayar
        ), 'penerimaan.xlsx');
    }

    private function getData($paginate = true)
    {
        $query = Pembayaran::with(['registrasi.pasien', 'pengguna.kepegawaianPegawai'])->whereDoesntHave('keuanganJurnal')->whereNull('registrasi_id')->whereBetween(DB::raw('DATE(tanggal)'), ['2026-01-01', '2026-01-31']);
        if (!auth()->user()->hasRole(['administrator', 'supervisor'])) {
            $query->where('pengguna_id', auth()->id());
        }
        return $query->get();
    }

    public $dataBarangApotek = [], $dataBarang = [];
    public $barang = [];
    public $keterangan;
    public $metode_bayar_2 = 1;
    public $cash = 0;
    public $cash_2 = 0;
    public $total_tagihan = 0;
    public $pasien_id;
    public $tanggal;
    public $total_bayar = 0;

    public $dataPasienTindakanResepObat = [], $cari, $registrasi, $dataNakes = [], $tindakan = [], $resep = [], $bahan = [], $alat = [], $total_tindakan = 0, $total_resep = 0, $total_barang = 0, $total_diskon_tindakan = 0, $total_diskon_barang = 0;



    private function barangTindakan($pembayaran)
    {
        return collect(BarangClass::stokKeluar(collect($this->bahan)->map(function ($q) {
            return [
                'barang_id' => $q['barang_id'],
                'barang_satuan_id' => $q['barang_satuan_id'],
                'qty' => $q['qty'],
                'harga' => 0,
                'diskon' => 0,
                'penjualan' => null,
                'kode_akun_id' => $q['kode_akun_id'],
                'kode_akun_penjualan_id' => $q['kode_akun_penjualan_id'],
                'kode_akun_modal_id' => $q['kode_akun_modal_id'],
                'rasio_dari_terkecil' => $q['rasio_dari_terkecil'],
            ];
        })->toArray(), $pembayaran->id, $pembayaran->tanggal))->groupBy('kode_akun_id')->map(fn($q) => [
            'kode_akun_id' => $q->first()['kode_akun_id'],
            'debet' => $q->sum('debet'),
            'kredit' => $q->sum('kredit'),
        ])->toArray();
    }

    private function kas($pembayaran)
    {
        $pendapatan = [
            [
                'kode_akun_id' => $pembayaran->kode_akun_id,
                'debet' => $pembayaran->bayar_2 > 0 ? $pembayaran->bayar : $pembayaran->total_tagihan,
                'kredit' => 0,
            ]
        ];
        if ($pembayaran->bayar_2 > 0) {
            $pendapatan = array_merge($pendapatan, [[
                'kode_akun_id' => $pembayaran->kode_akun_2_id,
                'debet' => $pembayaran->total_tagihan - $pembayaran->bayar,
                'kredit' => 0,
            ]]);
        }

        $pendapatan = array_merge($pendapatan, [
            [
                'kode_akun_id' =>  $this->getKodeAkunTransaksiByTransaksi('Diskon Pendapatan')->kode_akun_id,
                'debet' => $pembayaran->total_diskon_barang + $pembayaran->total_diskon_tindakan + $pembayaran->diskon,
                'kredit' => 0,
            ]
        ]);
        return $pendapatan;
    }

    private function tindakan($pembayaran)
    {
        foreach ($this->tindakan as $t) {
            Tindakan::where('id', $t['id'])->update([
                'diskon' => $t['diskon'],
                'perawat_id' => $t['perawat_id'] && $t['perawat_id'] != '-' ? $t['perawat_id'] : null
            ]);
        }
        $data = [];

        $bahan = $this->barangTindakan($pembayaran);

        $jasaDokter = collect($this->tindakan)->whereNotNull('dokter_id')->map(fn($q) => [
            'kode_akun_id' => collect($this->dataNakes)->firstWhere('id', $q['dokter_id'])['kode_akun_jasa_dokter_id'],
            'debet' => 0,
            'kredit' => $q['biaya_jasa_dokter'] * $q['qty'],
        ])->groupBy('kode_akun_id')->map(fn($q) => [
            'kode_akun_id' => $q->first()['kode_akun_id'],
            'debet' => $q->sum('debet'),
            'kredit' => $q->sum('kredit'),
        ])->toArray();

        $jasaPerawat = collect($this->tindakan)->whereNotNull('perawat_id')->map(fn($q) => [
            'kode_akun_id' => collect($this->dataNakes)->firstWhere('id', $q['perawat_id'])['kode_akun_jasa_perawat_id'],
            'debet' => 0,
            'kredit' => $q['biaya_jasa_perawat'] * $q['qty'],
        ])->groupBy('kode_akun_id')->map(fn($q) => [
            'kode_akun_id' => $q->first()['kode_akun_id'],
            'debet' => $q->sum('debet'),
            'kredit' => $q->sum('kredit'),
        ])->toArray();

        $hppJasaPelayan = [
            [
                'kode_akun_id' =>  $this->getKodeAkunTransaksiByTransaksi('HPP Jasa Pelayanan')->kode_akun_id,
                'debet' => collect($jasaDokter)->sum('kredit') + collect($jasaPerawat)->sum('kredit'),
                'kredit' => 0,
            ]
        ];


        $biayaPenyusutanAset = collect($this->alat)->where('metode_penyusutan', 'Satuan Hasil Produksi')->map(function ($q) {
            return [
                'kode_akun_id' => $this->getKodeAkunTransaksiByTransaksi('Biaya Penyusutan Aset')->kode_akun_id,
                'debet' => $q['biaya'] * $q['qty'],
                'kredit' => 0,
            ];
        })->all();

        $data = array_merge($data, $bahan); //bahan tindakan

        $data = array_merge($data, $jasaDokter); // Kewajiban Biaya Dokter

        $data = array_merge($data, $jasaPerawat); // Kewajiban Biaya Perawat

        $data = array_merge($data, $hppJasaPelayan); // HPP Jasa Pelayanan

        $data = array_merge($data, $biayaPenyusutanAset); // Biaya Penyusutan Aset

        $data = array_merge($data, collect($this->alat)->where('metode_penyusutan', 'Satuan Hasil Produksi')->map(function ($q) {
            return [
                'kode_akun_id' => $q['kode_akun_penyusutan_id'],
                'debet' => 0,
                'kredit' => $q['biaya'] * $q['qty'],
            ];
        })->all()); // HPP Aset  

        $data = array_merge($data, collect($this->tindakan)->map(function ($q) {
            return [
                'kode_akun_id' => $q['kode_akun_id'],
                'debet' => 0,
                'kredit' => $q['biaya'] * $q['qty'],
            ];
        })->all()); // Tindakan

        return collect($data)->groupBy('kode_akun_id')->map(fn($q) => [
            'kode_akun_id' => $q->first()['kode_akun_id'],
            'debet' => $q->sum('debet'),
            'kredit' => $q->sum('kredit'),
        ])->toArray();
    }

    private function resep($pembayaran)
    {
        $data = [];
        foreach ($this->resep as $resep) {
            $barangRaw = $resep['barang'] ?? [];
            $barangMap = collect($barangRaw)->map(function ($q) {
                $brg = collect($this->dataBarang)->firstWhere('id', $q['id']);
                return [
                    'qty' => $q['qty'],
                    'harga' => $q['harga'],
                    'diskon' => 0,
                    'penjualan' => null,
                    'kode_akun_id' => $brg['kode_akun_id'],
                    'kode_akun_penjualan_id' => $brg['kode_akun_penjualan_id'],
                    'kode_akun_modal_id' => $brg['kode_akun_modal_id'],
                    'barang_id' => $brg['barang_id'],
                    'barang_satuan_id' => $q['id'],
                    'rasio_dari_terkecil' => $brg['rasio_dari_terkecil'],
                ];
            })->all();
            if (count($barangMap)) {
                $hpp = BarangClass::stokKeluar($barangMap, $pembayaran->id, $pembayaran->tanggal);
                $data = array_merge($data, collect($hpp)->map(function ($q) {
                    return [
                        'kode_akun_id' => $q['kode_akun_id'],
                        'debet' => $q['debet'],
                        'kredit' => $q['kredit'],
                    ];
                })->all());
            }
        }

        return collect($data)->groupBy('kode_akun_id')->map(fn($q) => [
            'kode_akun_id' => $q->first()['kode_akun_id'],
            'debet' => $q->sum('debet'),
            'kredit' => $q->sum('kredit'),
        ])->toArray();
    }

    private function barangBebas($pembayaran)
    {
        $hpp = BarangClass::stokKeluar(collect($pembayaran->stokKeluar)->map(function ($q) {
            $brg = collect($this->dataBarang)->firstWhere('id', $q['barang_satuan_id']);
            return [
                'barang_id' => $brg['barang_id'],
                'barang_satuan_id' => $q['id'],
                'qty' => $q['qty'],
                'harga' => $q['harga'],
                'diskon' => $q['diskon'],
                'penjualan' => 1,
                'kode_akun_id' => $brg['kode_akun_id'],
                'kode_akun_penjualan_id' => $brg['kode_akun_penjualan_id'],
                'kode_akun_modal_id' => $brg['kode_akun_modal_id'],
                'rasio_dari_terkecil' => $brg['rasio_dari_terkecil'],
            ];
        })->toArray(), $pembayaran->id, $pembayaran->tanggal);
        return collect($hpp)->groupBy('kode_akun_id')->map(fn($q) => [
            'kode_akun_id' => $q->first()['kode_akun_id'],
            'debet' => $q->sum('debet'),
            'kredit' => $q->sum('kredit'),
        ])->toArray();
    }

    public function render()
    {
        ini_set('max_execution_time', 0);
        $data = $this->getData(true);
        foreach ($data as $item) {
            try {
                DB::transaction(function () use ($item) {
                    // $this->registrasi = Registrasi::find($item->registrasi_id);
                    // $this->barang = [];
                    $this->pasien_id = $item->pasien_id;

                    // $this->tindakan = $this->registrasi->tindakan->map(function ($q) {
                    //     return [
                    //         'id' => $q->id,
                    //         'tarif_tindakan_id' => $q->tarif_tindakan_id,
                    //         'nama' => $q->tarifTindakan->nama,
                    //         'diskon' => 0,
                    //         'qty' => $q->qty,
                    //         'kode_akun_id' => $q->tarifTindakan->kode_akun_id,
                    //         'harga' => $q->harga,
                    //         'catatan' => $q->catatan,
                    //         'dokter_id' => $q->dokter_id,
                    //         'perawat_id' => $q->perawat_id,
                    //         'biaya_alat' => collect($q->tindakanAlatBarang)->whereNotNull('aset_id')->sum(function ($q) {
                    //             return $q->qty * $q->biaya;
                    //         }),
                    //         'biaya_alat_barang' => $q->biaya_alat_barang,
                    //         'biaya_jasa_dokter' => $q->biaya_jasa_dokter,
                    //         'biaya_jasa_perawat' => $q->biaya_jasa_perawat,
                    //         'biaya' => $q->biaya,
                    //     ];
                    // })->toArray();

                    // $this->resep = collect($this->registrasi->resepObat)
                    //     ->groupBy('resep')
                    //     ->map(function ($group) {
                    //         $first = $group->first();
                    //         return [
                    //             'resep' => $first->resep,
                    //             'catatan' => $first->catatan,
                    //             'nama' => $first->nama,
                    //             'barang' => $group->map(function ($r) {
                    //                 $barang = collect($this->dataBarang)->firstWhere('id', $r->barang_satuan_id);
                    //                 if (!$barang) {
                    //                     return [
                    //                         'id' => null,
                    //                         'nama' => 'Terjadi Kesalahan Resep Obat',
                    //                         'satuan' => null,
                    //                         'kode_akun_id' => null,
                    //                         'kode_akun_penjualan_id' => null,
                    //                         'kode_akun_modal_id' => null,
                    //                         'harga' => null,
                    //                         'qty' => null,
                    //                         'subtotal' => null,
                    //                     ];
                    //                 }
                    //                 return [
                    //                     'id' => $r->barang_satuan_id,
                    //                     'nama' => $barang['nama'],
                    //                     'satuan' => $barang['satuan'],
                    //                     'kode_akun_id' => $barang['kode_akun_id'],
                    //                     'kode_akun_penjualan_id' => $barang['kode_akun_penjualan_id'],
                    //                     'kode_akun_modal_id' => $barang['kode_akun_modal_id'],
                    //                     'harga' => $r->harga,
                    //                     'qty' => $r->qty,
                    //                     'subtotal' => $r->harga * $r->qty,
                    //                 ];
                    //             })->toArray(),
                    //         ];
                    //     })->values()->toArray();

                    // $this->bahan = TindakanAlatBarang::whereNotNull('barang_satuan_id')->whereIn('tindakan_id', collect($this->tindakan)->pluck('id'))->get()->map(function ($q) {
                    //     $barang = collect($this->dataBarang)->firstWhere('id', $q->barang_satuan_id);
                    //     return [
                    //         'barang_id' => $barang['barang_id'],
                    //         'nama' => $barang['nama'],
                    //         'satuan' => $barang['satuan'],
                    //         'kode_akun_id' => $barang['kode_akun_id'],
                    //         'kode_akun_penjualan_id' => $barang['kode_akun_penjualan_id'],
                    //         'kode_akun_modal_id' => $barang['kode_akun_modal_id'],
                    //         'qty' => $q->qty,
                    //         'biaya' => $q->biaya,
                    //         'barang_satuan_id' => $q->barang_satuan_id,
                    //         'rasio_dari_terkecil' => $q->rasio_dari_terkecil,
                    //     ];
                    // })->toArray();

                    // $this->alat = TindakanAlatBarang::whereNotNull('aset_id')->where('biaya', '>', 0)->with('alat')->whereIn('tindakan_id', collect($this->tindakan)->pluck('id'))->get()->map(function ($q) {
                    //     return [
                    //         'id' => $q->aset_id,
                    //         'nama' => $q->alat->nama,
                    //         'metode_penyusutan' => $q->alat->metode_penyusutan,
                    //         'kode_akun_penyusutan_id' => $q->alat->kode_akun_penyusutan_id,
                    //         'qty' => $q->qty,
                    //         'biaya' => $q->biaya,
                    //         'kode_akun_id' => $q->alat->kode_akun_id,
                    //     ];
                    // })->toArray();
                    // if (count($this->tindakan) > 0) {
                    //     $this->dispatch('set-tindakan', data: $this->tindakan);
                    // }
                    // if (count($this->resep) > 0) {
                    //     $this->dispatch('set-resep', data: $this->resep);
                    // }
                    $this->total_tindakan = collect($this->tindakan)->sum(function ($q) {
                        return $q['biaya'] * $q['qty'] - $q['diskon'];
                    });
                    $this->total_resep = collect($this->resep)->sum(function ($q) {
                        return collect($q['barang'])->sum(function ($b) {
                            return $b['harga'] * $b['qty'];
                        });
                    });


                    $detail = $this->kas($item); // Kas dan diskon
                    if ($item->total_tindakan > 0) {
                        $detail = array_merge($detail, $this->tindakan($item)); // Pendapatan Tindakan
                    } // Pendapatan Tindakan
                    if ($item->total_resep > 0) {
                        $detail = array_merge($detail, $this->resep($item)); // Pendapatan Resep
                    } // Pendapatan Resep
                    if (collect($item)->filter(fn($item) => !empty($item['id']))->count() > 0) {
                        $detail = array_merge($detail, $this->barangBebas($item)); // Pendapatan Barang Bebas
                    }
                    $this->jurnalKeuangan($item, (collect($detail)->groupBy('kode_akun_id')->map(fn($q) => [
                        'kode_akun_id' => $q->first()['kode_akun_id'],
                        'debet' => $q->sum('debet'),
                        'kredit' => $q->sum('kredit'),
                    ])));
                });
            } catch (\Exception $e) {
                dd($e->getMessage(), $e, $item);
            }
        }
        return view('livewire.laporan.penerimaan.index', [
            'data' =>  $data->when($this->pengguna_id, fn($q) => $q->where('pengguna_id', $this->pengguna_id))->when($this->metode_bayar, fn($q) => $q->where('metode_bayar', $this->metode_bayar)),
            'dataPengguna' => auth()->user()->hasRole(['administrator', 'supervisor']) ? Pengguna::whereIn('id', $data->pluck('pengguna_id')->unique()->toArray())->get()->toArray() : Pengguna::where('id', auth()->id())->get()->toArray()
        ]);
    }

    private function jurnalKeuangan($pembayaran, $detail)
    {
        JurnalkeuanganClass::insert(
            jenis: 'Pendapatan',
            sub_jenis: 'Pendapatan ' . ($pembayaran->registrasi ? (collect($this->barang)->count() > 0 ? 'Pasien Tindakan/Resep Obat & Penjualan Barang' : 'Pasien Tindakan/Resep Obat') : 'Penjualan Barang Bebas'),
            tanggal: $pembayaran->tanggal,
            uraian: ('Pendapatan ' . ($pembayaran->registrasi ? (collect($this->barang)->count() > 0 ? 'Pasien Tindakan/Resep Obat & Penjualan Barang' : 'Pasien Tindakan/Resep Obat') : 'Penjualan Barang Bebas')) . ' No. Nota : ' . $pembayaran->id . ' a/n ' . ($pembayaran->registrasi ? $pembayaran?->registrasi?->pasien?->nama : $pembayaran?->pasien?->nama) . ' Ket : ' . $pembayaran->keterangan,
            system: 3,
            foreign_key: 'pembayaran_id',
            foreign_id: $pembayaran->id,
            detail: $detail
        );
    }
}
