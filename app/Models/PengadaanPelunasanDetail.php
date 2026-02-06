<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengadaanPelunasanDetail extends Model
{
    //
    protected $table = 'pengadaan_pelunasan_detail';

    public function pengadaanPelunasan(): BelongsTo
    {
        return $this->belongsTo(PengadaanPelunasan::class);
    }

    public function pengadaanTagihan()
    {
        return $this->belongsTo(PengadaanTagihan::class);
    }
}
