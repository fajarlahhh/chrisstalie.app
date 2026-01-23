<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanPengadaanDetail extends Model
{
    //
    protected $table = 'permintaan_pengadaan_detail';

    public function permintaanPengadaan()
    {
        return $this->belongsTo(PermintaanPengadaan::class);
    }

    public function barangSatuan()
    {
        return $this->belongsTo(BarangSatuan::class);
    }
}
