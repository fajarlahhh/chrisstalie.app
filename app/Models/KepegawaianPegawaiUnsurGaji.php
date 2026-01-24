<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KepegawaianPegawaiUnsurGaji extends Model
{
    //
    protected $table = 'kepegawaian_pegawai_unsur_gaji';

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(KepegawaianPegawai::class);
    }

    public function kodeAkun(): BelongsTo
    {
        return $this->belongsTo(KodeAkun::class);
    }
}
