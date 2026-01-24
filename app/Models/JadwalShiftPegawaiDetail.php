<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalShiftPegawaiDetail extends Model
{
    //
    protected $table = 'jadwal_shift_pegawai_detail';

    public function jadwalShiftPegawai(): BelongsTo
    {
        return $this->belongsTo(JadwalShiftPegawai::class);
    }
}
