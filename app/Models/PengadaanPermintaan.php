<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengadaanPermintaan extends Model
{
    //
    protected $table = 'pengadaan_permintaan';

    public function pengadaanPermintaanDetail()
    {
        return $this->hasMany(PengadaanPermintaanDetail::class);
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function pengadaanVerifikasi()
    {
        return $this->hasMany(PengadaanVerifikasi::class)->orderBy('created_at', 'desc')->where('jenis', 'Permintaan Pengadaan');
    }

    public function pengadaanVerifikasiPersetujuan(){
        return $this->hasOne(PengadaanVerifikasi::class, 'pengadaan_permintaan_id', 'id')->where('jenis', 'Persetujuan Pemesanan');
    }

    public function pengadaanVerifikasiPending()
    {
        return $this->hasMany(PengadaanVerifikasi::class)->whereNull('status')->where('jenis', 'Permintaan Pengadaan');
    }

    public function pengadaanVerifikasiDisetujui()
    {
        return $this->hasMany(PengadaanVerifikasi::class)->where('status', 'Disetujui')->where('jenis', 'Permintaan Pengadaan');
    }

    public function pengadaanVerifikasiDitolak()
    {
        return $this->hasMany(PengadaanVerifikasi::class)->where('status', 'Ditolak')->where('jenis', 'Permintaan Pengadaan');
    }

    public function pengadaanPemesanan()
    {
        return $this->hasMany(PengadaanPemesanan::class);
    }
}
