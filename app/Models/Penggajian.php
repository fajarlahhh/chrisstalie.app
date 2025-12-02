<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penggajian extends Model
{
    //
    protected $table = 'penggajian';

    protected $casts = [
        'detail' => 'array',
    ];

    public function kodeAkunPembayaran(): BelongsTo
    {
        return $this->belongsTo(KodeAkun::class, 'kode_akun_pembayaran_id');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}
