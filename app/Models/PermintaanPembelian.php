<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanPembelian extends Model
{
    //
    protected $table = 'permintaan_pembelian';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function permintaanPembelianDetail()
    {
        return $this->hasMany(PermintaanPembelianDetail::class);
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'referensi_id', 'id')->where('jenis', 'Permintaan Pembelian')->orderBy('created_at', 'desc');
    }

    public function verifikasiPending()
    {
        return $this->hasMany(Verifikasi::class, 'referensi_id', 'id')->where('jenis', 'Permintaan Pembelian')->whereNull('status');
    }

    public function verifikasiDisetujui()
    {
        return $this->hasMany(Verifikasi::class, 'referensi_id', 'id')->where('jenis', 'Permintaan Pembelian')->where('status', 'Disetujui');
    }

    public function verifikasiDitolak()
    {
        return $this->hasMany(Verifikasi::class, 'referensi_id', 'id')->where('jenis', 'Permintaan Pembelian')->where('status', 'Ditolak');
    }

    public function pembelian()
    {
        return $this->hasOne(Pembelian::class);
    }
}
