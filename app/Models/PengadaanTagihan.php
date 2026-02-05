<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengadaanTagihan extends Model
{
    protected $table = 'pengadaan_tagihan';

    public function pengadaanPemesanan(): BelongsTo
    {
        return $this->belongsTo(PengadaanPemesanan::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
