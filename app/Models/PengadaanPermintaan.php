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

    public function verifikasiPengadaan()
    {
        return $this->hasMany(VerifikasiPengadaan::class)->orderBy('created_at', 'desc');
    }

    public function VerifikasiPengadaanPending()
    {
        return $this->hasMany(VerifikasiPengadaan::class)->whereNull('status');
    }

    public function VerifikasiPengadaanDisetujui()
    {
        return $this->hasMany(VerifikasiPengadaan::class)->where('status', 'Disetujui');
    }

    public function VerifikasiPengadaanDitolak()
    {
        return $this->hasMany(VerifikasiPengadaan::class)->where('status', 'Ditolak');
    }

    public function pengadaanPemesanan()
    {
        return $this->hasOne(PengadaanPemesanan::class);
    }

    public function stokMasuk()
    {
        return $this->hasMany(StokMasuk::class);
    }
}
