<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelayananPemeriksaanAwal extends Model
{
    //
    protected $table = 'pelayanan_pemeriksaan_awal';
    protected $primaryKey = 'nomor';
    public $incrementing = false;
    protected $keyType = 'string';
    
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    /**
     * Get all of the ttv for the PelayananPemeriksaanAwal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pelayananPemeriksaanAwalDetail(): HasMany
    {
        return $this->hasMany(PelayananPemeriksaanAwalDetail::class);
    }

    /**
     * Get the pendaftaran that owns the PelayananPemeriksaanAwal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(PelayananPendaftaran::class);
    }
}
