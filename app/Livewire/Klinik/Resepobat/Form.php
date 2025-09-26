<?php

namespace App\Livewire\Klinik\Resepobat;

use App\Models\Stok;
use App\Models\Barang;
use Livewire\Component;
use App\Models\Penjualan;
use App\Models\Registrasi;
use App\Models\StokKeluar;
use App\Models\JurnalClass;
use App\Models\MetodeBayar;
use Illuminate\Support\Str;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $dataBarang = [], $dataMetodeBayar = [];
    public $barang = [];
    public $keterangan;
    public $metode_bayar = "Cash";
    public $cash = 0;
    public $total_harga_barang = 0;
    public $diskon = 0;
    public $data;
    public $resep = [];

    public function tambahBarang($index)
    {
        array_push($this->resep[$index]['barang'], [
            'id' => null,
            'barang_satuan_id' => null,
            'kode_akun_id' => null,
            'barangSatuan' => [],
            'qty' => 0,
            'harga' => 0,
            'rasio_dari_terkecil' => 0,
        ]);
    }

    public function updatedResep($value, $key)
    {
        $index = explode('.', $key);
        if (sizeof($index) == 4) {
            if ($value) {
                if ($index[3] == 'id') {
                    $barang = collect($this->dataBarang)->where('id', $value)->first();
                    $barangSatuan = collect($barang['barangSatuan']);
                    $this->resep[$index[0]]['barang'][$index[2]]['id'] =  $barang['id'] ?? null;
                    $this->resep[$index[0]]['barang'][$index[2]]['barang_satuan_id'] = null;
                    $this->resep[$index[0]]['barang'][$index[2]]['kode_akun_id'] = $barang['kode_akun_id'];
                    $this->resep[$index[0]]['barang'][$index[2]]['kode_akun_penjualan_id'] = $barang['kode_akun_penjualan_id'];
                    $this->resep[$index[0]]['barang'][$index[2]]['barangSatuan'] = $barangSatuan->toArray();
                    $this->resep[$index[0]]['barang'][$index[2]]['qty'] = $this->resep[$index[0]]['barang'][$index[2]]['qty'] ?? 0;
                }
            } else {
                $this->resep[$index[0]]['barang'][$index[2]]['id'] = null;
                $this->resep[$index[0]]['barang'][$index[2]]['barang_satuan_id'] = null;
                $this->resep[$index[0]]['barang'][$index[2]]['kode_akun_id'] = null;
                $this->resep[$index[0]]['barang'][$index[2]]['kode_akun_penjualan_id'] = null;
                $this->resep[$index[0]]['barang'][$index[2]]['barangSatuan'] = [];
                $this->resep[$index[0]]['barang'][$index[2]]['qty'] = 0;
            }
        }
    }

    public function hapusBarang($index, $key)
    {
        unset($this->barang[$index]['barang'][$key]);
        $this->barang = array_merge($this->barang[$index]['barang']);
        $this->total_harga_barang = collect($this->barang[$index]['barang'])->sum(fn($q) => $q['sub_total'] ?? 0);
    }

    public function submit()
    {
        $this->validate([
            'metode_bayar' => 'required',
            'cash' => $this->metode_bayar == 1 ? 'required|integer|min:' . ($this->total_harga_barang - $this->diskon) : 'nullable',
            'barang' => 'required|array',
            'barang.*.kode_akun_penjualan_id' => 'required',
            'barang.*.id' => 'required',
            'barang.*.barang_satuan_id' => 'required',
            'barang.*.harga' => 'required|integer',
            'barang.*.qty' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $barang = $this->barang[$index] ?? null;
                    if (!$barang) return;
                    // Cek stok tersedia
                    $stokTersedia = Stok::where('barang_id', $barang['id'])
                        ->available()
                        ->count();
                    if (($value / ($barang['rasio_dari_terkecil'] ?? 1)) > $stokTersedia) {
                        $fail('Stok barang tidak mencukupi. Stok tersedia: ' . $stokTersedia);
                    }
                }
            ],
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
            $data->total_harga_barang = $this->total_harga_barang;
            $data->diskon = $this->diskon;
            $data->total_tagihan = $this->total_harga_barang - $this->diskon;
            $data->bayar = $metodeBayar->nama == 'Cash' ? $this->cash : ($this->total_harga_barang - $this->diskon);
            $data->pengguna_id = auth()->id();
            $data->save();
            PenjualanDetail::insert(collect($this->barang)->map(fn($q) => [
                'qty' => $q['qty'],
                'harga' => $q['harga'],
                'penjualan_id' => $data->id,
                'barang_id' => $q['id'],
                'barang_satuan_id' => $q['barang_satuan_id'],
                'rasio_dari_terkecil' => $q['rasio_dari_terkecil'],
            ])->toArray());
            foreach ($this->barang as $barang) {
                $stokKeluarId = Str::uuid();
                StokKeluar::insert([
                    'id' => $stokKeluarId,
                    'tanggal' => now(),
                    'qty' => $barang['qty'],
                    'penjualan_id' => $data->id,
                    'barang_id' => $barang['id'],
                    'pengguna_id' => auth()->id(),
                    'barang_satuan_id' => $barang['barang_satuan_id'],
                    'rasio_dari_terkecil' => $barang['rasio_dari_terkecil'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                Stok::where('barang_id', $barang['id'])->available()->orderBy('tanggal_kedaluarsa', 'asc')->limit($barang['qty'])->update([
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

    public function mount(Registrasi $data)
    {
        $this->data = $data;
        $this->dataBarang = Barang::with(['barangSatuan.satuanKonversi', 'kodeAkun'])->where('perlu_resep', 0)->where('klinik', 0)->orderBy('nama')->get()->map(fn($q) => [
            'id' => $q['id'],
            'nama' => $q['nama'],
            'kode_akun_id' => $q['kode_akun_id'],
            'kode_akun_penjualan_id' => $q['kode_akun_penjualan_id'],
            'kategori' => $q->kodeAkun->nama,
            'barangSatuan' => $q['barangSatuan']->map(fn($r) => [
                'id' => $r['id'],
                'nama' => $r['nama'],
                'rasio_dari_terkecil' => $r['rasio_dari_terkecil'],
                'konversi_satuan' => $r['konversi_satuan'],
                'harga_jual' => $r['harga_jual'],
                'satuan_konversi' => $r['satuanKonversi'] ? [
                    'id' => $r['satuanKonversi']['id'],
                    'nama' => $r['satuanKonversi']['nama'],
                    'rasio_dari_terkecil' => $r['satuanKonversi']['rasio_dari_terkecil'],
                ] : null,
            ]),
        ])->toArray();
        if (!$data->resepobat) {
            $this->resep[] = [
                'barang' => [],
                'catatan' => '',
            ];
        }
    }

    public function tambahResep()
    {
        $this->resep[] = [
            'barang' => [],
            'catatan' => '',
        ];
    }

    public function hapusResep($index)
    {
        unset($this->resep[$index]);
        $this->resep = array_merge($this->resep);
    }

    public function render()
    {
        return view('livewire.klinik.resepobat.form');
    }
}
