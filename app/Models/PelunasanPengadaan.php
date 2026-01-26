<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelunasanPengadaan extends Model
{
    //
    protected $table = 'pelunasan_pengadaan';

    public function pengadaanPemesanan()
    {
        return $this->belongsTo(PengadaanPemesanan::class);
    }

    public function keuanganJurnal()
    {
        return $this->hasOne(KeuanganJurnal::class);
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function kodeAkunPembayaran()
    {
        return $this->belongsTo(KodeAkun::class);
    }
}
