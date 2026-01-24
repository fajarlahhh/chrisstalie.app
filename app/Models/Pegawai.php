<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pegawai';

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class)->withTrashed();
    }
    
    public function unsurGajiPegawai(): HasMany
    {
        return $this->hasMany(UnsurGajiPegawai::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function scopeNonAktif($query)
    {
        return $query->where('status', 'Non Aktif');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(AbsensiPegawai::class);
    }

    public function kehadiranPegawai(): HasMany
    {
        return $this->hasMany(KehadiranPegawai::class);
    }

    public function jadwalShiftPegawai(): HasMany
    {
        return $this->hasMany(JadwalShiftPegawai::class);
    }
}
