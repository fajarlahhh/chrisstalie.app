<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PegawaiUnsurGaji extends Model
{
    //
    protected $table = 'pegawai_unsur_gaji';
    protected $primaryKey = ['pegawai_id', 'unsur_gaji_id'];
    public $incrementing = false;
    protected $keyType = 'array';

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function unsurGajiKodeAkun(): BelongsTo
    {
        return $this->belongsTo(KodeAkun::class, 'unsur_gaji_kode_akun_id');
    }
}
