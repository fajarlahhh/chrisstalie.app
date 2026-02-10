<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Pemesanan;

use App\Models\KepegawaianPegawai;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\PengadaanPemesanan;
use Illuminate\Support\Facades\DB;
use App\Models\PengadaanPermintaan;
use App\Models\PengadaanVerifikasi;
use App\Models\Pengguna;
use App\Traits\CustomValidationTrait;

class Form extends Component
{
    use CustomValidationTrait;
    public $dataBarang = [], $dataPengguna = [], $barang = [], $deskripsi, $data, $verifikator_id, $status = 'Ditolak', $catatan, $dataSupplier = [], $supplier_id, $barangSudahDipesan = [], $tanggal, $tanggal_estimasi_kedatangan, $penanggung_jawab_id;

    public function submit()
    {
        // Validasi: jumlah total qty dari semua barang harus lebih dari 0
        $totalQty = array_sum(array_map(function ($b) {
            return isset($b['qty']) ? (float)$b['qty'] : 0;
        }, $this->barang));
        if ($totalQty <= 0) {
            $this->addError('barang', 'Total jumlah barang yang dipesan harus lebih dari 0.');
            return;
        }
        $this->validateWithCustomMessages([
            'tanggal_estimasi_kedatangan' => 'required|date',
            'supplier_id' => 'required|integer|exists:supplier,id',
            'penanggung_jawab_id' => 'required|integer|exists:pengguna,id',
            'barang' => 'required|array',
            'barang.*.id' => 'required|integer',
            'barang.*.qty' => [
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $matches = [];
                    if (preg_match('/^barang\.(\d+)\.qty$/', $attribute, $matches)) {
                        $index = (int)$matches[1];
                        if (isset($this->barang[$index]['qty_disetujui']) && $value > $this->barang[$index]['qty_disetujui']) {
                            $fail('Max ' . ($this->barang[$index]['qty_disetujui'] - $this->barang[$index]['qty_sudah_dipesan']) . ' ' . ($this->barang[$index]['satuan'] ?? ''));
                        }
                    }
                }
            ],
        ]);

        DB::transaction(function () {
            $data = new PengadaanPemesanan();
            if (!$data->exists) {
                $terakhir = PengadaanPemesanan::where('tanggal', 'like', date('Y-m') . '%')
                    ->orderBy('nomor', 'desc')
                    ->first();
                $nomorTerakhir = $terakhir ? (int)substr($terakhir->nomor, 0, 5) : 0;
                $nomor = sprintf('%05d', $nomorTerakhir + 1) . '/SP-CHRISSTALIE/' . date('m') . '/' . date('Y');
                $data->nomor = $nomor;
            }
            $data->tanggal = date('Y-m-d');
            $data->jenis = $this->data->jenis_barang;
            $data->tanggal_estimasi_kedatangan = $this->tanggal_estimasi_kedatangan;
            $data->catatan = $this->catatan;
            $data->supplier_id = $this->supplier_id;
            $data->pengadaan_permintaan_id = $this->data->id;
            $data->pengguna_id = auth()->id();
            $data->penanggung_jawab_id = $this->penanggung_jawab_id;
            $data->save();

            $data->pengadaanPemesananDetail()->delete();
            $data->pengadaanPemesananDetail()->insert(collect($this->barang)->map(fn($q) => [
                'qty' => $q['qty'],
                'harga_beli' => $q['harga_beli'],
                'barang_id' => $q['barang_id'],
                'barang_satuan_id' => $q['id'],
                'pengadaan_permintaan_id' => $this->data->id,
                'rasio_dari_terkecil' => $q['rasio_dari_terkecil'],
                'harga_beli_terkecil' => $q['harga_beli'] / $q['rasio_dari_terkecil'],
                'pengadaan_pemesanan_id' => $data->id,
            ])->toArray());


            $cetak = view('livewire.manajemenstok.pengadaanbrgdagang.pemesanan.cetak', [
                'data' => $data,
                'apoteker' => KepegawaianPegawai::where('apoteker', 1)->first(),
            ])->render();
            session()->flash('cetak', $cetak);
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/manajemenstok/pengadaanbrgdagang/pemesanan');
    }

    public function mount(PengadaanPermintaan $data, PengadaanPemesanan $pemesanan)
    {
        $this->data = $data;
        if ($this->data->pengadaanVerifikasi->where('status', 'Disetujui')->count() == 0) {
            return abort(404);
        }
        $this->fill($this->data->toArray());
        $this->barang = $data->pengadaanPermintaanDetail->map(fn($q) => [
            'id' => $q->barang_satuan_id,
            'barang_id' => $q->barang_id,
            'nama' => $q->barangSatuan->barang->nama,
            'satuan' => $q->barangSatuan->nama,
            'rasio_dari_terkecil' => $q->rasio_dari_terkecil,
            'qty_permintaan' => $q->qty_disetujui,
            'qty_sudah_dipesan' => $data->pengadaanPemesananDetail->where('barang_id', $q->barang_id)->sum('qty') ?? 0,
            'qty' => 0,
            'harga_beli' => 0,
        ])->toArray();
        $this->dataSupplier = Supplier::whereNotNull('konsinyator')->orderBy('nama')->get()->toArray();
        $this->dataPengguna = Pengguna::role(['supervisor', 'administrator'])->with('kepegawaianPegawai')->whereNotNull('kepegawaian_pegawai_id')->orderBy('nama')->get()->toArray();
    }

    public function render()
    {
        return view('livewire.manajemenstok.pengadaanbrgdagang.pemesanan.form');
    }
}
