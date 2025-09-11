<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nakes extends Model
{
    //
    protected $table = 'nakes';

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function getNamaAttribute()
    {
        if ($this->pegawai_id && $this->pegawai) {
            return $this->pegawai->nama;
        }
        return $this->attributes['nama'] ?? null;
    }

    public function getNikAttribute()
    {
        if ($this->pegawai_id && $this->pegawai) {
            return $this->pegawai->nik;
        }
        return $this->attributes['nik'] ?? null;
    }

    public function getAlamatAttribute()
    {
        if ($this->pegawai_id && $this->pegawai) {
            return $this->pegawai->alamat;
        }
        return $this->attributes['alamat'] ?? null;
    }
    
    public function getNoHpAttribute()
    {
        if ($this->pegawai_id && $this->pegawai) {
            return $this->pegawai->no_hp;
        }
        return $this->attributes['no_hp'] ?? null;
    }

    public function scopeDokter($query)
    {
        return $query->where('dokter', 1);
    }
}
