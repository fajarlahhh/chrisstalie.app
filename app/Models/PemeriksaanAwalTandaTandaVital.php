<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemeriksaanAwalTandaTandaVital extends Model
{
    //
    protected $table = 'pemeriksaan_awal_tanda_tanda_vital';

    public function pemeriksaanAwal(): BelongsTo
    {
        return $this->belongsTo(PemeriksaanAwal::class);
    }
}
