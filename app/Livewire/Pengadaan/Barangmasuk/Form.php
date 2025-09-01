<?php

namespace App\Livewire\Pengadaan\Barangmasuk;

use App\Models\Stok;
use Livewire\Component;
use App\Models\Pembelian;
use App\Models\StokMasuk;
use Illuminate\Support\Str;
use App\Models\PembelianDetail;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $dataPembelian = [], $barang = [];
    public $pembelian_id;

    public function updatedPembelianId()
    {
        $this->barang = [];
        $stokMasuk = StokMasuk::where('pembelian_id', $this->pembelian_id)->get()->map(fn($q) => [
            'id' => $q->barang_id,
            'qty_masuk' => $q->qty,
        ]);
        $barang = PembelianDetail::where('pembelian_id', $this->pembelian_id)->with('barang')->get()->map(fn($q) => [
            'id' => $q->barang_id,
            'nama' => $q->barang->nama,
            'satuan' => $q->barang->satuan,
            'qty' => $q->qty - ($stokMasuk->where('id', $q->barang_id)->first()['qty_masuk'] ?? 0),
            'qty_masuk' => null,
        ])->toArray();
        $this->barang = collect($barang)->filter(function ($q) {
            return $q['qty_masuk'] < $q['qty'];
        })->sortBy('barang_id')->values()->toArray();
    }

    public function mount()
    {
        $this->dataPembelian = Pembelian::select(DB::raw('pembelian.id id'), 'tanggal', 'supplier_id', 'uraian')
            ->leftJoin('pembelian_detail', 'pembelian.id', '=', 'pembelian_detail.pembelian_id')
            ->groupBy('pembelian.id', 'tanggal', 'supplier_id', 'uraian')
            ->havingRaw('SUM(pembelian_detail.qty) > (SELECT ifnull(SUM(stok_masuk.qty), 0) FROM stok_masuk WHERE pembelian_id = pembelian.id )')
            ->with('supplier')->get()->toArray();;
    }

    public function submit()
    {
        $this->validate([
            'pembelian_id' => 'required',
            'barang' => 'required|array',
        ]);

        DB::transaction(function () {
            $stokMasuk = [];
            foreach ($this->barang as $key => $value) {
                if ($value['qty_masuk'] > 0) {
                    $stokMasuk[] = [
                        'id' => Str::uuid(),
                        'qty' => $value['qty_masuk'],
                        'no_batch' => $value['no_batch'],
                        'tanggal_kedaluarsa' => $value['tanggal_kedaluarsa'],
                        'barang_id' => $value['id'],
                        'pembelian_id' => $this->pembelian_id,
                        'pengguna_id' => auth()->id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            StokMasuk::insert($stokMasuk);
            Stok::insert(collect($stokMasuk)->map(fn($q) => [
                'id' => $q['id'],
                'barang_id' => $q['barang_id'],
                'qty' => $q['qty'],
                'no_batch' => $q['no_batch'],
                'tanggal_kedaluarsa' => $q['tanggal_kedaluarsa'],
                'tanggal_masuk' => now(),
                'created_at' => $q['created_at'],
                'updated_at' => $q['updated_at'],
            ])->toArray());
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pengadaan/barangmasuk/form');
    }

    public function render()
    {
        return view('livewire.pengadaan.barangmasuk.form');
    }
}
