<?php

namespace App\Livewire\Pengadaan\Permintaan;

use App\Models\Barang;
use App\Models\Pengguna;
use Livewire\Component;
use App\Models\PermintaanPembelian;
use App\Models\PermintaanPembelianDetail;
use App\Models\Verifikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Form extends Component
{
    public $dataBarang = [], $dataPengguna = [], $barang = [], $previous, $deskripsi, $data, $verifikator_id;

    public function tambahBarang()
    {
        array_push($this->barang, [
            'id' => null,
            'satuan' => null,
            'qty' => 0,
        ]);
    }

    public function hapusbarang($key)
    {
        unset($this->barang[$key]);
        $this->barang = array_merge($this->barang);
    }

    public function submit()
    {
        $this->validate([
            'deskripsi' => 'required',
            'barang' => 'required|array',
            'barang.*.id' => 'required',
            'barang.*.satuan' => 'required',
            'barang.*.qty' => 'required',
        ]);

        DB::transaction(function () {

            if (!$this->data->exists) {
                $this->data->id = Str::uuid();
            }

            $this->data->deskripsi = $this->deskripsi;
            $this->data->pengguna_id = auth()->id();
            $this->data->save();

            $this->data->permintaanPembelianDetail()->delete();

            $this->data->permintaanPembelianDetail()->insert(collect($this->barang)->map(fn($q) => [
                'qty_permintaan' => $q['qty'],
                'permintaan_pembelian_id' => $this->data->id,
                'barang_id' => $q['id'],
            ])->toArray());

            if ($this->verifikator_id) {
                $verifikasi = new Verifikasi();
                $verifikasi->id = Str::uuid();
                $verifikasi->referensi_id = $this->data->id;
                $verifikasi->jenis = 'Permintaan Pembelian';
                $verifikasi->kantor = 'Apotek';
                $verifikasi->pengguna_id = $this->verifikator_id;
                $verifikasi->save();
            }

            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(PermintaanPembelian $data)
    {
        $this->previous = url()->previous();
        $this->dataBarang = Barang::with('barangSatuan')->orderBy('nama')->get()->map(fn($q) => [
            'id' => $q['id'],
            'nama' => $q['nama'],
            'barangSatuan' => $q['barangSatuan'],
        ])->toArray();
        $this->dataPengguna = Pengguna::where(fn($q) => $q->whereHas('permissions', function ($q) {
            $q->where('name', 'pengadaanverifikasi');
        })->orWhere(fn($q) => $q->whereHas('roles', fn($q) => $q->where('name', 'administrator'))))->orderBy('nama')->get()->toArray();
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->barang = $data->permintaanPembelianDetail->map(fn($q) => [
            'id' => $q->barang_id,
            'qty' => $q->qty_permintaan,
        ])->toArray();
    }

    public function render()
    {
        return view('livewire.pengadaan.permintaan.form');
    }
}
