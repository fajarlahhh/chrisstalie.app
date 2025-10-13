<?php

namespace App\Livewire\Penjualan;

use App\Models\Stok;
use App\Models\Barang;
use Livewire\Component;
use App\Models\Penjualan;
use App\Class\JurnalClass;
use App\Models\StokKeluar;
use App\Models\MetodeBayar;
use Illuminate\Support\Str;
use App\Models\PenjualanDetail;
use App\Class\BarangClass;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $dataBarang = [], $dataMetodeBayar = [];
    public $barang = [];
    public $keterangan;
    public $metode_bayar = 1;
    public $cash = 0;
    public $diskon = 0;
    public $total_tagihan = 0;

    public function submit()
    {
        $this->validate([
            'metode_bayar' => 'required',
            'cash' => $this->metode_bayar == 1 ? 'required|integer|min:' . ($this->total_tagihan) : 'nullable',
            'barang' => 'required|array',
            'barang.*.kode_akun_penjualan_id' => 'required',
            'barang.*.id' => 'required',
            'barang.*.id' => 'required',
            'barang.*.harga' => 'required|integer',
            // 'barang.*.qty' => [
            //     'required',
            //     'integer',
            //     'min:1',
            //     function ($attribute, $value, $fail) {
            //         $index = explode('.', $attribute)[1];
            //         $barang = $this->barang[$index] ?? null;
            //         if (!$barang) return;
            //         // Cek stok tersedia
            //         $stokTersedia = Stok::where('barang_id', $barang['id'])
            //             ->available()
            //             ->count();
            //         if (($value / ($barang['rasio_dari_terkecil'] ?? 1)) > $stokTersedia) {
            //             $fail('Stok barang tidak mencukupi. Stok tersedia: ' . $stokTersedia);
            //         }
            //     }
            // ],
        ]);

        DB::transaction(function () {
            $dataTerakhir = Penjualan::where('created_at', 'like',  date('Y-m') . '%')->orderBy('id', 'desc')->first();

            $metodeBayar = MetodeBayar::findOrFail($this->metode_bayar);

            if ($dataTerakhir) {
                $id = $dataTerakhir->id + 1;
            } else {
                $id = date('Ym') . '00001';
            }
            $data = new Penjualan();
            $data->id = $id;
            $data->keterangan = $this->keterangan;
            $data->metode_bayar = $metodeBayar->nama;
            $data->total_harga_barang = $this->total_tagihan + $this->diskon;
            $data->diskon = $this->diskon;
            $data->total_tagihan = $this->total_tagihan;
            $data->bayar = $metodeBayar->nama == 'Cash' ? $this->cash : ($this->total_tagihan);
            $data->pengguna_id = auth()->id();
            $data->save();

            $barang = collect($this->barang)->map(fn($q) => [
                'qty' => $q['qty'],
                'harga' => $q['harga'],
                'penjualan_id' => $data->id,
                'barang_id' => collect($this->dataBarang)->firstWhere('id', $q['id'])['barang_id'],
                'barang_satuan_id' => $q['id'],
                'rasio_dari_terkecil' => collect($this->dataBarang)->firstWhere('id', $q['id'])['rasio_dari_terkecil'],
            ])->toArray();
            PenjualanDetail::insert($barang);
            foreach ($barang as $brg) {
                $stokKeluarId = Str::uuid();
                StokKeluar::insert([
                    'id' => $stokKeluarId,
                    'tanggal' => now(),
                    'qty' => $brg['qty'],
                    'penjualan_id' => $data->id,
                    'barang_id' => $brg['barang_id'],
                    'pengguna_id' => auth()->id(),
                    'barang_satuan_id' => $brg['barang_satuan_id'],
                    'rasio_dari_terkecil' => $brg['rasio_dari_terkecil'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                Stok::where('barang_id', $brg['barang_id'])->available()->orderBy('tanggal_kedaluarsa', 'asc')->limit($brg['qty'])->update([
                    'tanggal_keluar' => now(),
                    'stok_keluar_id' => $stokKeluarId,
                ]);
            }

            $this->jurnalPendapatan($data, $metodeBayar);

            $cetak = view('livewire.penjualan.cetak', [
                'cetak' => true,
                'data' => Penjualan::findOrFail($data->id),
            ])->render();
            session()->flash('cetak', $cetak);
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('penjualan');
    }

    private function jurnalPendapatan($data, $metodeBayar)
    {
        $id = Str::uuid();
        $jurnalDetail = [];

        foreach (
            collect($this->barang)->groupBy('kode_akun_penjualan_id')->map(fn($q) => [
                'kode_akun_id' => $q->first()['kode_akun_penjualan_id'],
                'total' => $q->sum(fn($q) => $q['harga'] * $q['qty']),
            ]) as $barang
        ) {
            $jurnalDetail[] = [
                'jurnal_id' => $id,
                'debet' => 0,
                'kredit' => $barang['total'],
                'kode_akun_id' => $barang['kode_akun_id']
            ];
        }
        if ($this->diskon > 0) {
            $jurnalDetail[] = [
                'jurnal_id' => $id,
                'debet' => $this->diskon,
                'kredit' => 0,
                'kode_akun_id' => '44100'
            ];
        }
        $jurnalDetail[] = [
            'jurnal_id' => $id,
            'debet' => $this->total_tagihan,
            'kredit' => 0,
            'kode_akun_id' => $metodeBayar->kode_akun_id
        ];
        JurnalClass::insert($id, 'Penjualan', [
            'tanggal' => now(),
            'uraian' => 'Penjualan Barang Bebas ' . $data->id,
            'unit_bisnis' => 'Apotek',
            'referensi_id' => $data->id,
            'pengguna_id' => auth()->id(),
        ], $jurnalDetail);
    }

    public function mount()
    {
        $this->dataMetodeBayar = MetodeBayar::get()->toArray();
        $this->dataBarang = BarangClass::getBarang('apotek');
    }

    public function render()
    {
        return view('livewire.penjualan.index');
    }
}
