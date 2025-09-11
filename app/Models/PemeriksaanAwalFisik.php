<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemeriksaanAwalFisik extends Model
{
    //
    protected $table = 'pemeriksaan_awal_fisik';

    public function pemeriksaanAwal(): BelongsTo
    {
        return $this->belongsTo(PemeriksaanAwal::class);
    }
}
