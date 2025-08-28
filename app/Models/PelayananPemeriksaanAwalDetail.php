<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PelayananPemeriksaanAwalDetail extends Model
{
    use HasFactory;
    protected $table = 'pelayanan_pemeriksaan_fisik';
    protected $primaryKey = ['nomor', 'key'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(PelayananPendaftaran::class);
    }
}
