<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\KodeAkun;

class KeuanganJurnalDetail extends Model
{
    //
    protected $table = 'keuangan_jurnal_detail';

    public function keuanganJurnal(): BelongsTo
    {
        return $this->belongsTo(KeuanganJurnal::class);
    }

    public function kodeAkun(): BelongsTo
    {
        return $this->belongsTo(KodeAkun::class);
    }
}
