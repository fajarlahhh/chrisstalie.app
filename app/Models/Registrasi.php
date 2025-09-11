<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registrasi extends Model
{
    //
    protected $table = 'registrasi';

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function nakes(): BelongsTo
    {
        return $this->belongsTo(Nakes::class);
    }

    public function pemeriksaanAwal(): HasOne
    {
        return $this->hasOne(PemeriksaanAwal::class, 'id');
    }
}
