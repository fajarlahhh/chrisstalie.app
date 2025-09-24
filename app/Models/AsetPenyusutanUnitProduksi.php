<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AsetPenyusutanUnitProduksi extends Model
{
    //
    protected $table = 'aset_penyusutan_unit_produksi';
    public $timestamps = false;

    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class);
    }

    public function jurnal(): HasOne
    {
        return $this->hasOne(Jurnal::class);
    }
}
