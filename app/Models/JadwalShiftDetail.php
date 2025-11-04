<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalShiftDetail extends Model
{
    //
    protected $table = 'jadwal_shift_detail';

    public function jadwalShift(): BelongsTo
    {
        return $this->belongsTo(JadwalShift::class);
    }
}
