<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengadaanPelunasan extends Model
{
    //
    protected $table = 'pengadaan_pelunasan';

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
        return $this->belongsTo(Pengguna::class)->with('kepegawaianPegawai')->withTrashed();
    }

    public function kodeAkunPembayaran()
    {
        return $this->belongsTo(KodeAkun::class);
    }
    
    public function pengadaanPelunasanDetail(): HasMany
    {
        return $this->hasMany(PengadaanPelunasanDetail::class);
    }
}
