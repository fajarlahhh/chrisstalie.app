<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Pelunasan;

use Livewire\Component;
use App\Models\KodeAkun;
use App\Models\PengadaanPemesanan;
use App\Class\JurnalkeuanganClass;
use App\Models\PengadaanPelunasan;
use Illuminate\Support\Facades\DB;
use App\Traits\CustomValidationTrait;

class Form extends Component
{
    use CustomValidationTrait;
    public $pengadaanPemesanan, $dataPengadaanPemesanan = [], $dataKodePembayaran = [], $kode_akun_pembayaran_id, $pengadaan_pemesanan_id, $tanggal, $uraian;

    public function mount($data = null)
    {
        $this->dataPengadaanPemesanan = PengadaanPemesanan::where('pembayaran', 'Jatuh Tempo')->with('supplier', 'pengadaanPemesananDetail')
            ->whereDoesntHave('pengadaanPelunasanPemesanan')->get()->toArray();
        $this->dataKodePembayaran = KodeAkun::where('parent_id', '11100')->detail()->get()->toArray();
        if ($data) {
            $this->pengadaan_pemesanan_id = $data;
            $this->pengadaanPemesanan = PengadaanPemesanan::with('supplier', 'pengadaanPemesananDetail')->find($data);
        }
    }

    public function updatedPengadaanPemesananId()
    {
        $this->pengadaanPemesanan = PengadaanPemesanan::with('supplier', 'pengadaanPemesananDetail')->find($this->pengadaan_pemesanan_id);
    }

    public function submit()
    {
        $this->validateWithCustomMessages([
            'pengadaan_pemesanan_id' => 'required',
            'tanggal' => 'required',
            'uraian' => 'required',
            'kode_akun_pembayaran_id' => 'required',
        ]);

        DB::transaction(function () {
            $pengadaanPemesanan = PengadaanPemesanan::find($this->pengadaan_pemesanan_id);

            $data = new PengadaanPelunasan();
            $data->pengadaan_pemesanan_id = $this->pengadaan_pemesanan_id;
            $data->tanggal = $this->tanggal;
            $data->uraian = $this->uraian;
            $data->kode_akun_pembayaran_id = $this->kode_akun_pembayaran_id;
            $data->jumlah = $pengadaanPemesanan->total_harga;
            $data->save();

            JurnalkeuanganClass::insert(
                jenis: 'Pengeluaran',
                sub_jenis: 'Pelunasan Pembelian Barang Dagang',
                tanggal: $this->tanggal,
                uraian: $this->uraian,
                system: 1,
                foreign_key: 'pengadaan_pelunasan_id',
                foreign_id: $data->id,
                detail: [
                    [
                        'debet' => 0,
                        'kredit' => $pengadaanPemesanan->total_harga,
                        'kode_akun_id' => $this->kode_akun_pembayaran_id,
                    ],
                    [
                        'debet' => $pengadaanPemesanan->total_harga,
                        'kredit' => 0,
                        'kode_akun_id' => $pengadaanPemesanan->kode_akun_id,
                    ],
                ],
            );
            session()->flash('success', 'Berhasil menambahkan data');
        });

        $this->redirect('/manajemenstok/pengadaanbrgdagang/pelunasan');
    }
    public function render()
    {
        return view('livewire.manajemenstok.pengadaanbrgdagang.pelunasan.form');
    }
}
