<?php

namespace App\Livewire\Pengaturan\Koreksistok;

use App\Models\Stok;
use App\Models\Barang;
use Livewire\Component;
use App\Class\BarangClass;
use App\Class\JurnalClass;
use App\Models\StokKeluar;
use Illuminate\Support\Facades\DB;
use App\Traits\CustomValidationTrait;

class Form extends Component
{
    use CustomValidationTrait;
    public $dataBarang = [], $barang, $dataStok = [], $barang_id, $catatan, $qty_dikeluarkan;

    public function updatedBarangId($value)
    {
        $this->barang = collect($this->dataBarang)->firstWhere('id', $value);
    }

    public function mount()
    {
        $this->barang_id = '';
        $this->dataBarang = Stok::select('no_batch', 'barang_id', 'tanggal_kedaluarsa', 'harga_beli', DB::raw('COUNT(*) as qty'))->groupBy('no_batch', 'barang_id', 'tanggal_kedaluarsa', 'harga_beli')->whereNull('stok_keluar_id')->with('barang.barangSatuanTerkecil')->get()->map(fn($q) => [
            'id' => $q->barang_id,
            'nama' => $q->barang->nama,
            'satuan' => $q->barang->barangSatuanTerkecil->nama,
            'barang_satuan_id' => $q->barang->barangSatuanTerkecil->id,
            'tanggal_kedaluarsa' => $q->tanggal_kedaluarsa,
            'harga_beli' => $q->harga_beli,
            'kode_akun_id' => $q->barang->kode_akun_id,
            'kode_akun_modal_id' => $q->barang->kode_akun_modal_id,
            'qty' => $q->qty,
            'no_batch' => $q->no_batch,
        ])->toArray();
    }

    public function submit()
    {
        $this->validateWithCustomMessages([
            'barang_id' => 'required',
            'qty_dikeluarkan' => 'required',
        ]);

        DB::transaction(function () {
            if (Stok::where('barang_id', $this->barang_id)->where('no_batch', $this->barang['no_batch'])->where('tanggal_kedaluarsa', $this->barang['tanggal_kedaluarsa'])->count() < $this->qty_dikeluarkan) {
                session()->flash('error', 'Qty dikeluarkan melebihi stok yang tersedia');
                return $this->render();
            }
            $data = new StokKeluar();
            $data->tanggal = now();
            $data->barang_id = $this->barang_id;
            $data->qty = $this->qty_dikeluarkan;
            $data->harga = 0;
            $data->catatan = $this->catatan;
            $data->pengguna_id = auth()->id();
            $data->barang_satuan_id = $this->barang['barang_satuan_id'];
            $data->rasio_dari_terkecil = 1;
            $data->koreksi = 1;
            $data->save();
            Stok::where('barang_id', $this->barang_id)->where('no_batch', $this->barang['no_batch'])->where('tanggal_kedaluarsa', $this->barang['tanggal_kedaluarsa'])->update([
                'stok_keluar_id' => $data->id,
            ]);
            $this->jurnal($data);

            session()->flash('success', 'Berhasil menyimpan data');
        });
        return $this->redirect('/pengaturan/koreksistok');
    }
    private function jurnal($koreksi)
    {
        $detail[] = [
            'kode_akun_id' => $this->barang['kode_akun_id'],
            'debet' => 0,
            'kredit' => $this->barang['harga_beli'] * $this->qty_dikeluarkan,
        ];
        $detail[] = [
            'kode_akun_id' => $this->barang['kode_akun_modal_id'],
            'debet' => $this->barang['harga_beli'] * $this->qty_dikeluarkan,
            'kredit' => 0,
        ];

        JurnalClass::insert(
            jenis: 'Koreksi Stok Barang Bebas',
            sub_jenis: 'Koreksi Stok',
            tanggal: now(),
            uraian: 'Koreksi Stok Barang ' . $this->barang['nama'],
            system: 1,
            aset_id: null,
            pembelian_id: null,
            stok_masuk_id: null,
            pembayaran_id: null,
            penggajian_id: null,
            pelunasan_pembelian_id: null,
            stok_keluar_id: $koreksi->id,
            detail: $detail
        );
    }

    public function render()
    {
        return view('livewire.pengaturan.koreksistok.form');
    }
}
