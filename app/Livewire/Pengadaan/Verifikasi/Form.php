<?php

namespace App\Livewire\Pengadaan\Verifikasi;

use Livewire\Component;
use App\Models\PermintaanPembelian;
use App\Models\PermintaanPembelianDetail;
use App\Models\Verifikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Form extends Component
{
    public $dataBarang = [], $dataPengguna = [], $barang = [], $previous, $deskripsi, $data, $verifikator_id, $status = 'Ditolak';

    public function submit()
    {
        $this->validate([
            'status' => 'required',
        ]);

        if ($this->status == 'Disetujui') {
            $this->validate([
                'deskripsi' => 'required',
                'barang' => 'required|array',
                'barang.*.id' => 'required',
                'barang.*.qty_disetujui' => 'required|numeric|min:1',
            ]);
        }

        DB::transaction(function () {
            if ($this->status == 'Disetujui') {
                $this->data->permintaanPembelianDetail()->delete();
                $this->data->permintaanPembelianDetail()->insert(collect($this->barang)->map(fn($q) => [
                    'qty_permintaan' => $q['qty'],
                    'qty_disetujui' => $q['qty_disetujui'],
                    'permintaan_pembelian_id' => $this->data->id,
                    'barang_id' => $q['id'],
                ])->toArray());
            }
            $verifikasi = Verifikasi::where('referensi_id', $this->data->id)->where('jenis', 'Permintaan Pembelian')->whereNull('status')->first();
            $verifikasi->status = $this->status;
            $verifikasi->waktu_verifikasi = now();
            $verifikasi->save();

            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(PermintaanPembelian $data)
    {
        $this->previous = url()->previous();
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->barang = $data->permintaanPembelianDetail->map(fn($q) => [
            'id' => $q->barang_id,
            'nama' => $q->barang->nama,
            'qty' => $q->qty_permintaan,
            'qty_disetujui' => 0,
        ])->toArray();
    }

    public function render()
    {
        return view('livewire.pengadaan.verifikasi.form');
    }
}
