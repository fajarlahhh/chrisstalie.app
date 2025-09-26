<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TarifTindakanAlatBahan extends Model
{
    //
    protected $table = 'tarif_tindakan_alat_bahan';

    public function tarifTindakan(): BelongsTo
    {
        return $this->belongsTo(TarifTindakan::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class)->where('jenis', 'Bahan');
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(Aset::class)->where('jenis', 'Alat');
    }

    public function barangSatuan(): BelongsTo
    {
        return $this->belongsTo(BarangSatuan::class);
    }
}
