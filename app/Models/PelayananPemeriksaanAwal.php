<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelayananPemeriksaanAwal extends Model
{
    //
    protected $table = 'pelayanan_pemeriksaan_awal';
    
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    /**
     * Get all of the ttv for the PelayananPemeriksaanAwal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ttv(): HasMany
    {
        return $this->hasMany(PelayananPemeriksaanTtv::class);
    }

    /**
     * Get the pendaftaran that owns the PelayananPemeriksaanAwal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
